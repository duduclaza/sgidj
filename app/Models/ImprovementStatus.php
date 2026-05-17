<?php

namespace App\Models;

use App\Core\Model;

class ImprovementStatus extends Model
{
    protected string $table = 'melhoria_statuses';
    protected array $fillable = ['nome', 'cor', 'ordem'];

    public function allOrdered(): array
    {
        $statement = $this->db()->query("SELECT * FROM {$this->table} ORDER BY ordem ASC, nome ASC");
        return $statement->fetchAll();
    }
}
