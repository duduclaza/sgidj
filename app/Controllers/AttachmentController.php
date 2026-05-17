<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Attachment;
use App\Services\AuditLogger;
use App\Services\UploadService;
use Throwable;

class AttachmentController extends Controller
{
    public function store(string $improvementId): void
    {
        verify_csrf();
        if (!Auth::can(['admin', 'usuario'], 'anexar')) {
            $this->backWithError('Você não tem permissão para anexar arquivos.');
        }

        try {
            $file = (new UploadService())->store($_FILES['arquivo'] ?? []);
            $id = (new Attachment())->create($file + [
                'melhoria_id' => (int) $improvementId,
                'usuario_id' => Auth::id(),
            ]);
            AuditLogger::log('upload', 'anexos', $id);
            flash('success', 'Arquivo anexado.');
        } catch (Throwable $exception) {
            flash('error', $exception->getMessage());
        }

        redirect('/melhorias/' . $improvementId);
    }

    public function download(string $id): void
    {
        $attachment = (new Attachment())->find((int) $id);
        if (!$attachment) {
            http_response_code(404);
            exit('Arquivo não encontrado.');
        }

        $path = base_path($attachment['caminho']);
        if (!is_file($path)) {
            http_response_code(404);
            exit('Arquivo indisponível no storage.');
        }

        header('Content-Type: ' . $attachment['mime_type']);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="' . basename($attachment['nome_original']) . '"');
        readfile($path);
        exit;
    }

    public function destroy(string $id): void
    {
        verify_csrf();
        $model = new Attachment();
        $attachment = $model->find((int) $id);
        if ($attachment) {
            $path = base_path($attachment['caminho']);
            if (is_file($path)) {
                unlink($path);
            }
            $model->delete((int) $id);
            AuditLogger::log('exclusão', 'anexos', (int) $id);
        }
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('/melhorias')));
        exit;
    }
}
