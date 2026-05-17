<?php

namespace App\Models;

use App\Core\Model;

class Meeting extends Model
{
    protected string $table = 'reunioes';
    protected array $fillable = ['melhoria_id', 'tema', 'data', 'horario', 'participantes', 'melhorias_discutidas', 'decisoes', 'proximas_acoes', 'ata_resumo', 'criado_por'];

    public function list(array $filters = []): array
    {
        $sql = 'SELECT r.*, u.nome AS criador_nome, m.ticket AS melhoria_ticket, m.titulo AS melhoria_titulo 
                FROM reunioes r 
                LEFT JOIN usuarios u ON u.id = r.criado_por 
                LEFT JOIN melhorias m ON m.id = r.melhoria_id
                WHERE 1=1';
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= ' AND (r.tema LIKE :q OR r.participantes LIKE :q OR m.ticket LIKE :q OR m.titulo LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['melhoria_id'])) {
            $sql .= ' AND r.melhoria_id = :melhoria_id';
            $params['melhoria_id'] = $filters['melhoria_id'];
        }

        $sql .= ' ORDER BY r.data DESC, r.horario DESC';
        $statement = $this->db()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function byImprovement(int $improvementId): array
    {
        return $this->list(['melhoria_id' => $improvementId]);
    }
}
