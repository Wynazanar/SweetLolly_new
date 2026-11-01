<?php
namespace Project\Models;

use Core\Model;

class Game extends Model
{
    public function allWithCategories(): array
    {
        return $this->findMany(
            "SELECT m.name, m.card_text, c.name AS category_name, m.tags, m.path_image
             FROM minigames m
             INNER JOIN minigames_categories c ON m.category_id = c.id
             ORDER BY m.name"
        );
    }

    public function findByName(string $name): ?array
    {
        return $this->findOne(
            "SELECT * FROM minigames WHERE name = ? LIMIT 1",
            [$name]
        );
    }

    public function allCategories(): array
    {
        return $this->findMany("SELECT * FROM minigames_categories ORDER BY id");
    }
}