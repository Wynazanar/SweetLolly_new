<?php
namespace Project\Models;

use Core\Model;

class User extends Model
{
    public function findByLogin(string $login): ?array
    {
        $login = mysqli_real_escape_string(self::$link, $login);

        $row = $this->findOne(
            "SELECT * FROM players 
             WHERE nickname = '$login' OR email = '$login' 
             LIMIT 1"
        );

        return $row ?: null;
    }

    public function findByNickname(string $nickname): ?array
    {
        $nickname = mysqli_real_escape_string(self::$link, $nickname);

        $row = $this->findOne(
            "SELECT id, nickname, email, created_at, updated_at 
            FROM players 
            WHERE nickname = '$nickname' 
            LIMIT 1"
        );

        return $row ?: null;
    }

    public function create(string $nickname, string $email, string $password): int
    {
        $hash     = password_hash($password, PASSWORD_DEFAULT);
        $nickname = mysqli_real_escape_string(self::$link, $nickname);
        $email    = mysqli_real_escape_string(self::$link, $email);
        $hash     = mysqli_real_escape_string(self::$link, $hash);

        mysqli_query(
            self::$link,
            "INSERT INTO players (nickname, email, password_hash) 
             VALUES ('$nickname', '$email', '$hash')"
        ) or die(mysqli_error(self::$link));

        return (int) mysqli_insert_id(self::$link);
    }

    public function exists(string $nickname, string $email): bool
    {
        $nickname = mysqli_real_escape_string(self::$link, $nickname);
        $email    = mysqli_real_escape_string(self::$link, $email);

        $row = $this->findOne(
            "SELECT id FROM players 
             WHERE nickname = '$nickname' OR email = '$email' 
             LIMIT 1"
        );

        return (bool) $row;
    }
}