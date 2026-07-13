<?php
	namespace Project\Models;
	use \Core\Model;
	
	class Page extends Model
	{
		public function getById($id)
		{
			return $this->findOne("SELECT * FROM page WHERE id=$id");
		}
		
		public function getAll()
		{
			return $this->findMany("SELECT id, title FROM page");
		}

		public function getAllGames()
		{
			return $this->findMany("SELECT m.name, m.card_text, c.name as category_name, m.tags, m.path_image
									FROM minigames m
									INNER JOIN minigames_categories c ON m.category_id = c.id
									ORDER BY m.name;");
		}

		public function getGameByName($game)
		{
			return $this->findOne("SELECT * FROM minigames WHERE name = '$game';");
		}

		public function getAllGameCategories()
		{
			return $this->findMany("SELECT * FROM minigames_categories;");
		}

		public function getRules()
		{
			return $this->findMany("
				SELECT * FROM rules ORDER BY id");
		}

		public function getRuleCategories()
		{
			return $this->findMany("SELECT * FROM rules_categories ORDER BY id");
		}

		public function getAllSubs()
		{
			return $this->findMany("SELECT * FROM subs ORDER BY id");
		}
	}
