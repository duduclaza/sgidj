<?php

namespace App\Models;

use App\Core\Model;

class Attachment extends Model
{
    protected string $table = 'anexos';
    protected array $fillable = ['melhoria_id', 'usuario_id', 'nome_original', 'caminho', 'mime_type', 'tamanho'];

    public function byImprovement(int $improvementId): array
    {
        $statement = $this->db()->prepare(
            'SELECT a.*, u.nome AS usuario_nome
             FROM anexos a
             INNER JOIN usuarios u ON u.id = a.usuario_id
             WHERE a.melhoria_id = :id
             ORDER BY a.created_at DESC'
        );
        $statement->execute(['id' => $improvementId]);
        return $statement->fetchAll();
    }
}
