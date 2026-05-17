<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function create(int $userId, string $title, string $message, string $type = 'info', ?string $link = null): int
    {
        return (new Notification())->create([
            'usuario_id' => $userId,
            'titulo' => $title,
            'mensagem' => $message,
            'tipo' => $type,
            'lida' => 0,
            'link' => $link,
        ]);
    }
}
