<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Improvement;
use App\Models\Pdca;
use App\Models\User;
use App\Services\AuditLogger;

class PdcaController extends Controller
{
    private array $stageStatuses = ['Pendente', 'Em andamento', 'Concluída', 'Bloqueada'];

    public function index(): void
    {
        $this->view('pdca/index', [
            'title' => 'PDCA',
            'improvements' => (new Improvement())->list(['q' => $_GET['q'] ?? '']),
        ]);
    }

    public function edit(string $improvementId): void
    {
        $improvement = (new Improvement())->findWithRelations((int) $improvementId);
        if (!$improvement) {
            flash('error', 'Melhoria não encontrada.');
            redirect('/pdca');
        }

        $this->view('pdca/form', [
            'title' => 'PDCA',
            'improvement' => $improvement,
            'pdca' => (new Pdca())->findByImprovement((int) $improvementId),
            'users' => (new User())->list(),
            'stageStatuses' => $this->stageStatuses,
        ]);
    }

    public function update(string $improvementId): void
    {
        verify_csrf();
        (new Pdca())->upsert((int) $improvementId, $this->payload());
        AuditLogger::log('edição', 'pdca', (int) $improvementId);
        flash('success', 'PDCA salvo.');
        redirect('/pdca/' . $improvementId . '/editar');
    }

    private function payload(): array
    {
        $data = [];
        foreach (['plan', 'do', 'check', 'act'] as $stage) {
            $data[$stage . '_text'] = trim((string) ($_POST[$stage . '_text'] ?? ''));
            $data[$stage . '_status'] = (string) ($_POST[$stage . '_status'] ?? 'Pendente');
            $data[$stage . '_responsavel_id'] = ($_POST[$stage . '_responsavel_id'] ?? '') !== '' ? (int) $_POST[$stage . '_responsavel_id'] : null;
            $data[$stage . '_prazo'] = ($_POST[$stage . '_prazo'] ?? '') !== '' ? $_POST[$stage . '_prazo'] : null;
        }

        return $data;
    }
}
