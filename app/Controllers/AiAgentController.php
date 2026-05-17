<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Improvement;
use App\Services\AIService;
use App\Services\AuditLogger;

class AiAgentController extends Controller
{
    public function index(): void
    {
        if (!Auth::can(['super_admin', 'admin', 'usuario'], 'ia')) {
            http_response_code(403);
            require base_path('views/errors/403.php');
            return;
        }

        $this->view('ia/index', [
            'title' => 'Analista de Melhoria Contínua',
            'improvements' => (new Improvement())->list(),
            'result' => $_SESSION['_ai_result'] ?? null,
        ]);
        unset($_SESSION['_ai_result']);
    }

    public function assist(): void
    {
        verify_csrf();
        if (!Auth::can(['super_admin', 'admin', 'usuario'], 'ia')) {
            $this->backWithError('Você não tem permissão para usar o agente de IA.');
        }

        $task = (string) ($_POST['task'] ?? 'estrutura');
        $context = trim((string) ($_POST['contexto'] ?? ''));
        $result = (new AIService())->assist($task, $context);
        $_SESSION['_ai_result'] = $result;
        AuditLogger::log('consulta de IA', 'ia', null, ['task' => $task]);
        redirect('/ia');
    }
}
