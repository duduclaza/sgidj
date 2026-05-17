<?php

namespace App\Models;

use App\Core\Model;

class Swot extends Model
{
    protected string $table = 'swot';
    protected array $fillable = ['melhoria_id', 'forcas', 'fraquezas', 'oportunidades', 'ameacas'];

    public function findByImprovement(int $improvementId): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM swot WHERE melhoria_id = :id LIMIT 1');
        $statement->execute(['id' => $improvementId]);
        $item = $statement->fetch();
        return $item ?: null;
    }

    public function upsert(int $improvementId, array $data): int
    {
        $existing = $this->findByImprovement($improvementId);
        $data['melhoria_id'] = $improvementId;
        if ($existing) {
            $this->update((int) $existing['id'], $data);
            return (int) $existing['id'];
        }

        return $this->create($data);
    }
}
