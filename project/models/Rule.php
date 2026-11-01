<?php
namespace Project\Models;

use Core\Model;

class Rule extends Model
{
    public function all(): array
    {
        return $this->findMany("SELECT * FROM rules ORDER BY id");
    }

    public function allCategories(): array
    {
        return $this->findMany("SELECT * FROM rules_categories ORDER BY id");
    }
}