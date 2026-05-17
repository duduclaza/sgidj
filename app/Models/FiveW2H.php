<?php

namespace App\Models;

use App\Core\Model;

class FiveW2H extends Model
{
    protected string $table = 'planos_5w2h';
    protected array $fillable = ['melhoria_id', 'what_text', 'why_text', 'where_text', 'when_text', 'who_text', 'how_text', 'how_much'];

    public function findByImprovement(int $improvementId): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM planos_5w2h WHERE melhoria_id = :id LIMIT 1');
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
