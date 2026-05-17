<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\FiveW2H;
use App\Models\Improvement;
use App\Services\AuditLogger;

class FiveW2HController extends Controller
{
    public function index(): void
    {
        $this->view('5w2h/index', [
            'title' => '5W2H',
            'improvements' => (new Improvement())->list(['q' => $_GET['q'] ?? '']),
        ]);
    }

    public function edit(string $improvementId): void
    {
        $improvement = (new Improvement())->findWithRelations((int) $improvementId);
        if (!$improvement) {
            flash('error', 'Melhoria não encontrada.');
            redirect('/5w2h');
        }

        $this->view('5w2h/form', [
            'title' => '5W2H',
            'improvement' => $improvement,
            'plan' => (new FiveW2H())->findByImprovement((int) $improvementId),
        ]);
    }

    public function update(string $improvementId): void
    {
        verify_csrf();
        (new FiveW2H())->upsert((int) $improvementId, [
            'what_text' => trim((string) ($_POST['what_text'] ?? '')),
            'why_text' => trim((string) ($_POST['why_text'] ?? '')),
            'where_text' => trim((string) ($_POST['where_text'] ?? '')),
            'when_text' => trim((string) ($_POST['when_text'] ?? '')),
            'who_text' => trim((string) ($_POST['who_text'] ?? '')),
            'how_text' => trim((string) ($_POST['how_text'] ?? '')),
            'how_much' => trim((string) ($_POST['how_much'] ?? '')),
        ]);
        AuditLogger::log('edição', 'planos_5w2h', (int) $improvementId);
        flash('success', 'Plano 5W2H salvo.');
        redirect('/5w2h/' . $improvementId . '/editar');
    }
}
