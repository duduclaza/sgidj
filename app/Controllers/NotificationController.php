<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(): void
    {
        $this->view('notificacoes/index', [
            'title' => 'Notificações',
            'notifications' => (new Notification())->forUser((int) Auth::id()),
        ]);
    }

    public function markRead(string $id): void
    {
        verify_csrf();
        (new Notification())->update((int) $id, ['lida' => 1]);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('/notificacoes')));
        exit;
    }
}
