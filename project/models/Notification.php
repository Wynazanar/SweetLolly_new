<?php
namespace Project\Models;

use Core\Model;

class Notification extends Model
{
    private static bool $tableReady = false;

    private function ensureTable(): void
    {
        if (self::$tableReady) {
            return;
        }

        mysqli_query(self::$link, "
            CREATE TABLE IF NOT EXISTS site_notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                player_name VARCHAR(32) NOT NULL,
                event_type VARCHAR(64) NOT NULL,
                payload JSON NULL,
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_player_read (player_name, is_read),
                INDEX idx_player_id (player_name, id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        self::$tableReady = true;
    }

    /** @return \mysqli_result|bool */
    private function query(string $sql)
    {
        $res = mysqli_query(self::$link, $sql);
        if ($res === false && mysqli_errno(self::$link) === 1146) {
            self::$tableReady = false;
            $this->ensureTable();
            $res = mysqli_query(self::$link, $sql);
        }
        return $res;
    }

    public function create(string $playerName, string $event, array $payload): void
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $event = mysqli_real_escape_string(self::$link, $event);
        $json = mysqli_real_escape_string(self::$link, json_encode($payload, JSON_UNESCAPED_UNICODE));

        $this->query("
            INSERT INTO site_notifications (player_name, event_type, payload)
            VALUES ('$playerName', '$event', '$json')
        ");
    }

    public function unreadFor(string $playerName): array
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $res = $this->query("
            SELECT id, event_type, payload, created_at
            FROM site_notifications
            WHERE player_name = '$playerName' AND is_read = 0
            ORDER BY id DESC
            LIMIT 50
        ");
        $rows = [];
        if ($res === false) {
            return $rows;
        }
        while ($row = mysqli_fetch_assoc($res)) {
            $row['payload'] = json_decode($row['payload'], true) ?: [];
            $rows[] = $row;
        }
        return $rows;
    }

    public function markRead(int $id, string $playerName): void
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $this->query("
            UPDATE site_notifications
            SET is_read = 1
            WHERE id = $id AND player_name = '$playerName'
        ");
    }

    public function markFriendRequestRead(string $playerName, string $fromNick): void
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $fromNick = mysqli_real_escape_string(self::$link, $fromNick);

        // MySQL 5.7+ / 8: JSON_UNQUOTE + JSON_EXTRACT
        $this->query("
            UPDATE site_notifications
            SET is_read = 1
            WHERE player_name = '$playerName'
            AND is_read = 0
            AND event_type = 'friend_request'
            AND JSON_UNQUOTE(JSON_EXTRACT(payload, '$.from')) = '$fromNick'
        ");
    }

    public function recentFor(string $playerName, int $limit = 30): array
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $limit = max(1, min(50, $limit));
        $res = $this->query("
            SELECT id, event_type, payload, is_read, created_at
            FROM site_notifications
            WHERE player_name = '$playerName'
            ORDER BY id DESC
            LIMIT $limit
        ");
        $rows = [];
        if ($res === false) {
            return $rows;
        }
        while ($row = mysqli_fetch_assoc($res)) {
            $row['payload'] = json_decode($row['payload'], true) ?: [];
            $row['is_read'] = (int)$row['is_read'];
            $rows[] = $row;
        }
        return $rows;
    }

    public function unreadCount(string $playerName): int
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $res = $this->query("
            SELECT COUNT(*) AS c FROM site_notifications
            WHERE player_name = '$playerName' AND is_read = 0
        ");
        if ($res === false) {
            return 0;
        }
        $row = mysqli_fetch_assoc($res);
        return (int)($row['c'] ?? 0);
    }

    /** Прочитать всё, кроме активных friend_request (их гасим только accept/reject) */
    public function markOpened(string $playerName): void
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $this->query("
            UPDATE site_notifications
            SET is_read = 1
            WHERE player_name = '$playerName'
            AND is_read = 0
            AND event_type <> 'friend_request'
        ");
    }

    public function unreadAfterId(string $playerName, int $afterId): array
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $afterId = (int)$afterId;
        $res = $this->query("
            SELECT id, event_type, payload, is_read, created_at
            FROM site_notifications
            WHERE player_name = '$playerName' AND id > $afterId
            ORDER BY id ASC
            LIMIT 20
        ");
        $rows = [];
        if ($res === false) {
            return $rows;
        }
        while ($row = mysqli_fetch_assoc($res)) {
            $row['payload'] = json_decode($row['payload'], true) ?: [];
            $row['is_read'] = (int)$row['is_read'];
            $rows[] = $row;
        }
        return $rows;
    }
}