<?php

namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    protected string $table = 'logs_auditoria';
    protected array $fillable = ['usuario_id', 'acao', 'tabela', 'registro_id', 'detalhes', 'ip'];

    public function list(array $filters = []): array
    {
        $sql = 'SELECT l.*, u.nome AS usuario_nome FROM logs_auditoria l LEFT JOIN usuarios u ON u.id = l.usuario_id WHERE 1=1';
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= ' AND (l.acao LIKE :q OR l.tabela LIKE :q OR l.detalhes LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql .= ' ORDER BY l.created_at DESC LIMIT 300';
        $statement = $this->db()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }
}
