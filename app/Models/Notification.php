<?php

namespace App\Models;

use App\Core\Model;

class Notification extends Model
{
    protected string $table = 'notificacoes';
    protected array $fillable = ['usuario_id', 'titulo', 'mensagem', 'tipo', 'lida', 'link'];

    public function forUser(int $userId): array
    {
        $statement = $this->db()->prepare('SELECT * FROM notificacoes WHERE usuario_id = :id ORDER BY created_at DESC LIMIT 100');
        $statement->execute(['id' => $userId]);
        return $statement->fetchAll();
    }

    public function unreadCount(int $userId): int
    {
        $statement = $this->db()->prepare('SELECT COUNT(*) FROM notificacoes WHERE usuario_id = :id AND lida = 0');
        $statement->execute(['id' => $userId]);
        return (int) $statement->fetchColumn();
    }
}
