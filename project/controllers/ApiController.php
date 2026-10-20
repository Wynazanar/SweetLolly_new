<?php
namespace Project\Controllers;

use Core\Controller;
use Project\Services\BridgeApi;
use Project\Models\Notification;

class ApiController extends Controller
{
    /**
     * GET /api/top/:endpoint?page=&limit=&orderBy=&order=&search=
     */
    public function top($params)
    {
        header('Content-Type: application/json; charset=utf-8');

        $endpoint = strtolower(trim($params['endpoint'] ?? ''));

        $allowed = [
            'bw-top', 'bow-top', 'bow-top-mode', 'boxing-top',
            'classic-top', 'uhc-top', 'sumo-top', 'bridge-solo',
        ];
        if (!in_array($endpoint, $allowed, true)) {
            http_response_code(404);
            echo json_encode(['error' => 'Unknown endpoint']);
            exit;
        }

        // простой rate limit
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!$this->rateLimit('top:' . $ip, 60, 60)) {
            http_response_code(429);
            echo json_encode(['error' => 'Too many requests']);
            exit;
        }

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $limit   = min(100, max(1, (int)($_GET['limit'] ?? 20)));
        $orderBy = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['orderBy'] ?? '') ?: null;
        $order   = strtolower($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $search  = trim($_GET['search'] ?? '');

        $bridge = new BridgeApi();
        $result = $bridge->getTop($endpoint, [
            'page'    => $page,
            'limit'   => $limit,
            'orderBy' => $orderBy,
            'order'   => $order,
            'search'  => $search !== '' ? $search : null,
        ]);

        if ($result === null) {
            http_response_code(502);
            echo json_encode(['error' => 'API unavailable']);
            exit;
        }

        // тот же формат, что отдаёт плагин
        echo json_encode([
            $endpoint => $result['rows'],
            'meta'    => $result['meta'],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function rateLimit(string $key, int $max, int $seconds): bool
    {
        $file = sys_get_temp_dir() . '/rl_' . md5($key) . '.json';
        $data = file_exists($file)
            ? (json_decode((string)file_get_contents($file), true) ?: ['n' => 0, 't' => time()])
            : ['n' => 0, 't' => time()];

        if (time() - $data['t'] > $seconds) {
            $data = ['n' => 0, 't' => time()];
        }
        $data['n']++;
        file_put_contents($file, json_encode($data), LOCK_EX);

        return $data['n'] <= $max;
    }

    /**
     * POST /api/internal/notify
     * Принимает уведомления от плагина BedwarsAPI
     */
    public function notify()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
        }

        $cfg = require dirname(__DIR__) . '/config/notify.php';
        $key = $_SERVER['HTTP_X_API_KEY'] ?? '';

        if (empty($cfg['api_key']) || !hash_equals((string)$cfg['api_key'], (string)$key)) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid API key']);
            exit;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw ?: '', true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            exit;
        }

        $event = (string)($data['event'] ?? '');
        $to = trim((string)($data['to'] ?? ''));
        $payload = is_array($data['data'] ?? null) ? $data['data'] : [];

        if ($event === '' || $to === '') {
            http_response_code(400);
            echo json_encode(['error' => 'event and to required']);
            exit;
        }

        (new Notification())->create($to, $event, $payload);

        echo json_encode(['ok' => true]);
        exit;
    }
}