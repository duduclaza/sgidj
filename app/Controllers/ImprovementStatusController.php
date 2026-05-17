<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\ImprovementStatus;
use App\Services\AuditLogger;

class ImprovementStatusController extends Controller
{
    public function index(): void
    {
        if (!Auth::can('admin')) {
            http_response_code(403);
            require base_path('views/errors/403.php');
            return;
        }

        $this->view('statuses/index', [
            'title' => 'Configuração de Status',
            'statuses' => (new ImprovementStatus())->allOrdered(),
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        if (!Auth::can('admin')) {
            $this->backWithError('Permissão negada.');
        }

        $nome = trim($_POST['nome'] ?? '');
        if ($nome === '') {
            $this->backWithError('O nome do status é obrigatório.');
        }

        (new ImprovementStatus())->create([
            'nome' => $nome,
            'cor' => $_POST['cor'] ?? 'bg-slate-500',
            'ordem' => (int) ($_POST['ordem'] ?? 0),
        ]);

        AuditLogger::log('criação', 'melhoria_statuses', 0, ['nome' => $nome]);
        flash('success', 'Status cadastrado com sucesso.');
        redirect('/configuracao/status');
    }

    public function update(string $id): void
    {
        verify_csrf();
        if (!Auth::can('admin')) {
            $this->backWithError('Permissão negada.');
        }

        $nome = trim($_POST['nome'] ?? '');
        if ($nome === '') {
            $this->backWithError('O nome do status é obrigatório.');
        }

        (new ImprovementStatus())->update((int) $id, [
            'nome' => $nome,
            'cor' => $_POST['cor'] ?? 'bg-slate-500',
            'ordem' => (int) ($_POST['ordem'] ?? 0),
        ]);

        AuditLogger::log('edição', 'melhoria_statuses', (int) $id, ['nome' => $nome]);
        flash('success', 'Status atualizado.');
        redirect('/configuracao/status');
    }

    public function destroy(string $id): void
    {
        verify_csrf();
        if (!Auth::can('admin')) {
            $this->backWithError('Permissão negada.');
        }

        (new ImprovementStatus())->delete((int) $id);
        AuditLogger::log('exclusão', 'melhoria_statuses', (int) $id);
        flash('success', 'Status removido.');
        redirect('/configuracao/status');
    }
}
