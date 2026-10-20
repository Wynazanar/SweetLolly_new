<?php
namespace Project\Models;

use Core\Model;

class Notification extends Model
{
    public function create(string $playerName, string $event, array $payload): void
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $event = mysqli_real_escape_string(self::$link, $event);
        $json = mysqli_real_escape_string(self::$link, json_encode($payload, JSON_UNESCAPED_UNICODE));

        mysqli_query(self::$link, "
            INSERT INTO site_notifications (player_name, event_type, payload)
            VALUES ('$playerName', '$event', '$json')
        ");
    }

    public function unreadFor(string $playerName): array
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        $res = mysqli_query(self::$link, "
            SELECT id, event_type, payload, created_at
            FROM site_notifications
            WHERE player_name = '$playerName' AND is_read = 0
            ORDER BY id DESC
            LIMIT 50
        ");
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $row['payload'] = json_decode($row['payload'], true) ?: [];
            $rows[] = $row;
        }
        return $rows;
    }

    public function markRead(int $id, string $playerName): void
    {
        $playerName = mysqli_real_escape_string(self::$link, $playerName);
        mysqli_query(self::$link, "
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
        mysqli_query(self::$link, "
            UPDATE site_notifications
            SET is_read = 1
            WHERE player_name = '$playerName'
            AND is_read = 0
            AND event_type = 'friend_request'
            AND JSON_UNQUOTE(JSON_EXTRACT(payload, '$.from')) = '$fromNick'
        ");
    }
}