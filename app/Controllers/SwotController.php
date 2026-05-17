<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Improvement;
use App\Models\Swot;
use App\Services\AuditLogger;

class SwotController extends Controller
{
    public function index(): void
    {
        $this->view('swot/index', [
            'title' => 'SWOT',
            'improvements' => (new Improvement())->list(['q' => $_GET['q'] ?? '']),
        ]);
    }

    public function edit(string $improvementId): void
    {
        $improvement = (new Improvement())->findWithRelations((int) $improvementId);
        if (!$improvement) {
            flash('error', 'Melhoria não encontrada.');
            redirect('/swot');
        }

        $this->view('swot/form', [
            'title' => 'SWOT',
            'improvement' => $improvement,
            'swot' => (new Swot())->findByImprovement((int) $improvementId),
        ]);
    }

    public function update(string $improvementId): void
    {
        verify_csrf();
        (new Swot())->upsert((int) $improvementId, [
            'forcas' => trim((string) ($_POST['forcas'] ?? '')),
            'fraquezas' => trim((string) ($_POST['fraquezas'] ?? '')),
            'oportunidades' => trim((string) ($_POST['oportunidades'] ?? '')),
            'ameacas' => trim((string) ($_POST['ameacas'] ?? '')),
        ]);
        AuditLogger::log('edição', 'swot', (int) $improvementId);
        flash('success', 'SWOT salva.');
        redirect('/swot/' . $improvementId . '/editar');
    }
}
