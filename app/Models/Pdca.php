<?php

namespace App\Models;

use App\Core\Model;

class Pdca extends Model
{
    protected string $table = 'pdca';
    protected array $fillable = [
        'melhoria_id',
        'plan_text',
        'plan_status',
        'plan_responsavel_id',
        'plan_prazo',
        'do_text',
        'do_status',
        'do_responsavel_id',
        'do_prazo',
        'check_text',
        'check_status',
        'check_responsavel_id',
        'check_prazo',
        'act_text',
        'act_status',
        'act_responsavel_id',
        'act_prazo',
    ];

    public function findByImprovement(int $improvementId): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM pdca WHERE melhoria_id = :id LIMIT 1');
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
