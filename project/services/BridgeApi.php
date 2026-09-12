<?php

namespace Project\Services;

class BridgeApi
{
    public const ERR_NETWORK = 'network';
    public const ERR_ENDPOINT = 'endpoint_not_found';
    public const ERR_SERVER = 'server_error';
    public const ERR_INVALID_JSON = 'invalid_json';

    private string $baseUrl;
    private string $apiKey;
    private int $timeout;
    private ?string $lastError = null;

    public function __construct(?array $config = null)
    {
        if ($config === null) {
            $config = require dirname(__DIR__) . '/config/bridge.php';
        }

        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');
        $this->apiKey  = (string) ($config['api_key'] ?? '');
        $this->timeout = (int) ($config['timeout'] ?? 8);
    }

    /**
     * @return array|null Ответ API или null при ошибке сети / невалидном JSON
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function login(string $login, string $password): ?array
    {
        return $this->post('/v1/auth/login', [
            'login'    => $login,
            'password' => $password,
        ]);
    }

    /**
     * @param array<string, string> $player Параметры игрока (uuid, username и т.д.)
     * @return array|null
     */
    public function dataQuery(string $queryName, array $player): ?array
    {
        return $this->post('/v1/data/query', [
            'query'  => $queryName,
            'player' => $player,
        ]);
    }

    /**
     * Профиль из nLogin по нику.
     *
     * @return array<string, mixed>|null
     */
    public function profileByUsername(string $username): ?array
    {
        $username = trim($username);
        if ($username === '') {
            return null;
        }

        $response = $this->dataQuery('nlogin_by_username', ['username' => $username]);
        if ($response === null || empty($response['success'])) {
            return null;
        }

        $rows = $response['rows'] ?? [];
        if (!is_array($rows) || $rows === []) {
            return null;
        }

        return $this->mapPublicProfile($rows[0]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function profileByUuid(string $uuid): ?array
    {
        $uuid = trim($uuid);
        if ($uuid === '') {
            return null;
        }

        $response = $this->dataQuery('nlogin_profile', ['uuid' => $uuid]);
        if ($response === null || empty($response['success'])) {
            return null;
        }

        $rows = $response['rows'] ?? [];
        if (!is_array($rows) || $rows === []) {
            return null;
        }

        return $this->mapPublicProfile($rows[0]);
    }

    /**
     * @return array{user: ?array, bridgeAvailable: bool}
     */
    public function resolveProfile(string $nickname, ?string $fallbackUuid = null): array
    {
        $nickname = trim($nickname);
        $bridgeAvailable = true;

        $response = $this->dataQuery('nlogin_by_username', ['username' => $nickname]);
        if ($response === null) {
            $bridgeAvailable = false;
        } elseif (!empty($response['success'])) {
            $rows = $response['rows'] ?? [];
            if (is_array($rows) && $rows !== []) {
                return [
                    'user' => $this->mapPublicProfile($rows[0]),
                    'bridgeAvailable' => true,
                ];
            }
        }

        $uuid = trim($fallbackUuid ?? '');
        if ($uuid !== '') {
            $uuidResponse = $this->dataQuery('nlogin_profile', ['uuid' => $uuid]);
            if ($uuidResponse === null) {
                $bridgeAvailable = false;
            } elseif (!empty($uuidResponse['success'])) {
                $rows = $uuidResponse['rows'] ?? [];
                if (is_array($rows) && $rows !== []) {
                    return [
                        'user' => $this->mapPublicProfile($rows[0]),
                        'bridgeAvailable' => $bridgeAvailable,
                    ];
                }
            }
        }

        return ['user' => null, 'bridgeAvailable' => $bridgeAvailable];
    }

    public function playerPoints(string $uuid, ?string $nickname = null): ?int
    {
        $uuid = trim($uuid);
        $candidates = $uuid !== '' ? $this->uuidLookupVariants($uuid) : [];

        $nickname = trim($nickname ?? '');
        if ($nickname !== '') {
            foreach (array_unique([$nickname, strtolower($nickname)], SORT_STRING) as $nickVariant) {
                $offline = $this->offlinePlayerUuid($nickVariant);
                if ($offline !== null) {
                    $candidates = array_merge($candidates, $this->uuidLookupVariants($offline));
                }
            }
        }

        $candidates = array_values(array_unique($candidates));
        if ($candidates === []) {
            return null;
        }

        $bridgeFailed = false;

        foreach ($candidates as $variant) {
            $response = $this->dataQuery('playerpoints_points', ['uuid' => $variant]);
            if ($response === null) {
                $bridgeFailed = true;
                continue;
            }

            $points = $this->parsePointsResponse($response);
            if ($points !== null) {
                return $points;
            }
        }

        return $bridgeFailed ? null : 0;
    }

    /**
     * Как в test.js: points.rows[0]?.points ?? 0
     */
    private function parsePointsResponse(array $response): ?int
    {
        if (empty($response['success'])) {
            return null;
        }

        $rows = $response['rows'] ?? [];
        if (!is_array($rows) || $rows === []) {
            return null;
        }

        $row = $this->normalizeRowKeys($rows[0]);
        if (!array_key_exists('points', $row) || !is_numeric($row['points'])) {
            return null;
        }

        return (int) round((float) $row['points']);
    }

    /** UUID offline-игрока (Java: OfflinePlayer:nick), как в PlayerPoints. */
    private function offlinePlayerUuid(string $nickname): ?string
    {
        if ($nickname === '') {
            return null;
        }

        $hash = md5('OfflinePlayer:' . $nickname, true);
        $hash[6] = chr((ord($hash[6]) & 0x0f) | 0x30);
        $hash[8] = chr((ord($hash[8]) & 0x3f) | 0x80);
        $hex = bin2hex($hash);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }

    /**
     * nLogin и PlayerPoints часто хранят UUID в разном виде (с дефисами / без).
     *
     * @return list<string>
     */
    private function uuidLookupVariants(string $uuid): array
    {
        $uuid = trim($uuid);
        $variants = [$uuid];

        $compact = strtolower(str_replace('-', '', $uuid));
        if (strlen($compact) === 32 && ctype_xdigit($compact)) {
            $dashed = sprintf(
                '%s-%s-%s-%s-%s',
                substr($compact, 0, 8),
                substr($compact, 8, 4),
                substr($compact, 12, 4),
                substr($compact, 16, 4),
                substr($compact, 20, 12)
            );

            $variants[] = $compact;
            $variants[] = $dashed;
            $variants[] = strtoupper($compact);
            $variants[] = strtoupper($dashed);
        }

        return array_values(array_unique($variants));
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    public function mapPublicProfile(array $row): array
    {
        $row = $this->normalizeRowKeys($row);

        return [
            'nickname'      => (string) ($row['last_name'] ?? ''),
            'unique_id'     => isset($row['unique_id']) ? (string) $row['unique_id'] : null,
            'email'         => $row['email'] ?? null,
            'last_ip'       => $row['last_ip'] ?? null,
            'last_login'    => $row['last_login'] ?? null,
            'creation_date' => $row['creation_date'] ?? null,
        ];
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function normalizeRowKeys(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            if (is_string($key)) {
                $normalized[strtolower($key)] = $value;
            } else {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array|null
     */
    private function post(string $path, array $payload): ?array
    {
        $this->lastError = null;

        if ($this->baseUrl === '') {
            error_log('Bridge API: base_url не задан');
            $this->lastError = self::ERR_NETWORK;
            return null;
        }

        $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if ($body === false) {
            return null;
        }

        $ch = curl_init($this->baseUrl . $path);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'key: ' . $this->apiKey,
                'Content-Length: ' . strlen($body),
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            error_log("Bridge API error ($path): $error");
            $this->lastError = self::ERR_NETWORK;
            return null;
        }

        if ($httpCode === 404) {
            error_log("Bridge API endpoint not found ($path)");
            $this->lastError = self::ERR_ENDPOINT;
            return null;
        }

        if ($httpCode >= 500) {
            error_log("Bridge API HTTP $httpCode ($path): $response");
            $this->lastError = self::ERR_SERVER;
            return null;
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            error_log("Bridge API invalid JSON ($path): $response");
            $this->lastError = self::ERR_INVALID_JSON;
            return null;
        }

        return $data;
    }
}
