<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Comment;
use App\Models\Improvement;
use App\Services\AuditLogger;
use App\Services\NotificationService;

class CommentController extends Controller
{
    public function store(string $improvementId): void
    {
        verify_csrf();
        if (!Auth::can(['admin', 'usuario'], 'comentar')) {
            $this->backWithError('Você não tem permissão para comentar.');
        }

        $comment = trim((string) ($_POST['comentario'] ?? ''));
        if ($comment === '') {
            $this->backWithError('Digite um comentário antes de enviar.');
        }

        $id = (new Comment())->create([
            'melhoria_id' => (int) $improvementId,
            'usuario_id' => Auth::id(),
            'comentario' => $comment,
        ]);

        $improvement = (new Improvement())->find((int) $improvementId);
        if ($improvement && !empty($improvement['responsavel_id']) && (int) $improvement['responsavel_id'] !== Auth::id()) {
            (new NotificationService())->create((int) $improvement['responsavel_id'], 'Novo comentário', $improvement['titulo'], 'comentario', '/melhorias/' . $improvementId);
        }

        AuditLogger::log('comentário', 'comentarios', $id);
        flash('success', 'Comentário registrado.');
        redirect('/melhorias/' . $improvementId);
    }

    public function destroy(string $id): void
    {
        verify_csrf();
        (new Comment())->delete((int) $id);
        AuditLogger::log('exclusão', 'comentarios', (int) $id);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('/melhorias')));
        exit;
    }
}
