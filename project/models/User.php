<?php
	namespace Project\Models;
	use \Core\Model;
	
	class User extends Model
	{
		public function findByNickname(string $nickname): ?array {
			$nickname = $this->escape($nickname);

			$row = $this->findOne(
				"SELECT * FROM players
				WHERE nickname = '$nickname' 
				LIMIT 1"
			);
			return $row ?: null;
		}

		public function create(string $nickname, string $password): int {
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$nickname = $this->escape($nickname);
			$hash = $this->escape($hash);

			mysqli_query(self::$link, 
				"INSERT INTO players (nickname, password_hash)
				 VALUES ('$nickname', '$hash')"
			);

			return mysqli_insert_id(self::$link);
		}

		private function escape(string $v): string {
			return mysqli_real_escape_string(self::$link, $v);
		}
		
	}