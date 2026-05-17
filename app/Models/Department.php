<?php

namespace App\Models;

use App\Core\Model;

class Department extends Model
{
    protected string $table = 'departamentos';
    protected array $fillable = ['nome', 'responsavel_id', 'status'];

    public function list(array $filters = []): array
    {
        $sql = 'SELECT d.*, u.nome AS responsavel_nome
                FROM departamentos d
                LEFT JOIN usuarios u ON u.id = d.responsavel_id
                WHERE 1=1';
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= ' AND d.nome LIKE :q';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['status'])) {
            $sql .= ' AND d.status = :status';
            $params['status'] = $filters['status'];
        }

        $sql .= ' ORDER BY d.nome ASC';
        $statement = $this->db()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function active(): array
    {
        return $this->db()->query("SELECT * FROM departamentos WHERE status = 'ativo' ORDER BY nome ASC")->fetchAll();
    }
}
