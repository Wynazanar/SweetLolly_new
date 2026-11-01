<?php
namespace Project\Models;

use Core\Model;

class Subscription extends Model
{
    public function all(): array
    {
        return $this->findMany("SELECT * FROM subs ORDER BY id");
    }
}