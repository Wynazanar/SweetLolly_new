<?php

namespace Project\Services;

class BridgeApi
{
    public const ERR_NETWORK = 'network';
    public const ERR_ENDPOINT = 'endpoint_not_found';
    public const ERR_SERVER = 'server_error';
    public const ERR_INVALID_JSON = 'invalid_json';

    private string $baseUrl;
    private int $timeout;
    private ?string $lastError = null;

    public function __construct(?array $config = null)
    {
        if ($config === null) {
            $config = require dirname(__DIR__) . '/config/bridge.php';
        }

        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');
        $this->timeout = (int) ($config['timeout'] ?? 8);
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Авторизация через BedwarsAPI
     * Успех → массив с полями last_name, unique_id, email и т.д.
     * Ошибка → null
     */
    public function login(string $login, string $password): ?array
    {
        $data = $this->post('/auth', [
            'login'    => $login,
            'password' => $password,
        ]);

        if ($data === null) {
            return null;
        }

        // Наш плагин при ошибке возвращает {"error": "..."}
        if (isset($data['error'])) {
            $this->lastError = $data['error'];
            return null;
        }

        // Приводим к удобному виду для сайта
        return [
            'success'       => true,
            'username'      => $data['last_name'] ?? $login,
            'unique_id'     => $data['unique_id'] ?? null,
            'email'         => $data['email'] ?? null,
            'last_login'    => $data['last_login'] ?? null,
            'creation_date' => $data['creation_date'] ?? null,
            'discord'       => $data['discord'] ?? null,
            'raw'           => $data,
        ];
    }

    /**
     * Регистрация через BedwarsAPI
     */
    public function register(string $login, string $password): ?array
    {
        $data = $this->post('/register', [
            'login'    => $login,
            'password' => $password,
        ]);

        if ($data === null) {
            return null;
        }

        if (isset($data['error'])) {
            $this->lastError = $data['error'];
            return null;
        }

        return [
            'success'   => true,
            'username'  => $data['last_name'] ?? $login,
            'unique_id' => $data['unique_id'] ?? null,
            'message'   => $data['message'] ?? 'Регистрация успешна',
            'raw'       => $data,
        ];
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

        // 401 от плагина тоже приходит как JSON с error
        if ($httpCode === 401 && isset($data['error'])) {
            $this->lastError = $data['error'];
            return $data; // вернём, чтобы login() увидел error
        }

        return $data;
    }

    /**
     * Профиль из nlogin через BedwarsAPI
     */
    public function getProfile(string $nickname): ?array
    {
        $data = $this->get('/profile/' . rawurlencode($nickname));
        if ($data === null || isset($data['error'])) {
            $this->lastError = $data['error'] ?? self::ERR_NETWORK;
            return null;
        }

        return [
            'nickname'      => $data['last_name'] ?? $nickname,
            'unique_id'     => $data['unique_id'] ?? null,
            'email'         => $data['email'] ?? null,
            'discord'       => $data['discord'] ?? null,
            'last_ip'       => $data['last_ip'] ?? null,
            'last_login'    => $data['last_login'] ?? null,
            'creation_date' => $data['creation_date'] ?? null,
            'prefix'        => $data['prefix'] ?? 'Игрок',
        ];
    }

    /**
     * Статистика одного игрока
     */
    public function getPlayerStats(string $endpoint, string $nickname): ?array
    {
        $data = $this->get('/' . $endpoint . '/' . rawurlencode($nickname));
        if ($data === null || isset($data['error'])) {
            return null;
        }

        $rows = $data[$endpoint] ?? [];
        if (!is_array($rows) || $rows === []) {
            return null;
        }
        return $rows[0];
    }

    /**
     * Статистика всех мини-игр
     */
    public function getMinigameStats(string $nickname): array
    {
        $map = [
            'BedWars'      => 'bw-top',
            'Classic Duel' => 'classic-top',
            'Bow Duel'     => 'bow-top',
            'Boxing Duel'  => 'boxing-top',
            'UHC Duel'     => 'uhc-top',
            'Sumo'         => 'sumo-top',
            'Bridge Solo'  => 'bridge-solo',
        ];

        $result = [];
        foreach ($map as $title => $endpoint) {
            $stats = $this->getPlayerStats($endpoint, $nickname);
            if ($stats !== null) {
                $result[$title] = $stats;
            }
        }
        return $result;
    }

    /**
     * GET-запрос к плагину
     */
    private function get(string $path): ?array
    {
        $this->lastError = null;

        if ($this->baseUrl === '') {
            $this->lastError = self::ERR_NETWORK;
            return null;
        }

        $ch = curl_init($this->baseUrl . $path);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            $this->lastError = self::ERR_NETWORK;
            return null;
        }

        if ($httpCode === 404) {
            $this->lastError = self::ERR_ENDPOINT;
            return null;
        }

        if ($httpCode >= 500) {
            $this->lastError = self::ERR_SERVER;
            return null;
        }

        $data = json_decode($response, true);
        return is_array($data) ? $data : null;
    }
}