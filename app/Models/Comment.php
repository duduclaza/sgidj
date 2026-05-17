<?php

namespace App\Models;

use App\Core\Model;

class Comment extends Model
{
    protected string $table = 'comentarios';
    protected array $fillable = ['melhoria_id', 'usuario_id', 'comentario'];

    public function byImprovement(int $improvementId): array
    {
        $statement = $this->db()->prepare(
            'SELECT c.*, u.nome AS autor_nome
             FROM comentarios c
             INNER JOIN usuarios u ON u.id = c.usuario_id
             WHERE c.melhoria_id = :id
             ORDER BY c.created_at DESC'
        );
        $statement->execute(['id' => $improvementId]);
        return $statement->fetchAll();
    }
}
