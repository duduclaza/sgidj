<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Improvement;
use App\Models\Meeting;
use App\Services\AuditLogger;

class MeetingController extends Controller
{
    public function index(): void
    {
        $filters = ['q' => $_GET['q'] ?? ''];
        $this->view('reunioes/index', [
            'title' => 'Reuniões',
            'meetings' => (new Meeting())->list($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        $this->view('reunioes/form', [
            'title' => 'Nova reunião',
            'meeting' => null,
            'improvements' => (new Improvement())->list(),
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        $data = $this->payload();
        $data['criado_por'] = Auth::id();
        $id = (new Meeting())->create($data);
        AuditLogger::log('criação', 'reunioes', $id);
        flash('success', 'Reunião registrada.');
        redirect('/reunioes');
    }

    public function edit(string $id): void
    {
        $meeting = (new Meeting())->find((int) $id);
        if (!$meeting) {
            flash('error', 'Reunião não encontrada.');
            redirect('/reunioes');
        }

        $this->view('reunioes/form', [
            'title' => 'Editar reunião',
            'meeting' => $meeting,
            'improvements' => (new Improvement())->list(),
        ]);
    }

    public function update(string $id): void
    {
        verify_csrf();
        (new Meeting())->update((int) $id, $this->payload());
        AuditLogger::log('edição', 'reunioes', (int) $id);
        flash('success', 'Reunião atualizada.');
        redirect('/reunioes');
    }

    public function destroy(string $id): void
    {
        verify_csrf();
        (new Meeting())->delete((int) $id);
        AuditLogger::log('exclusão', 'reunioes', (int) $id);
        flash('success', 'Reunião removida.');
        redirect('/reunioes');
    }

    private function payload(): array
    {
        $theme = trim((string) ($_POST['tema'] ?? ''));
        if ($theme === '') {
            $this->backWithError('O tema da reunião é obrigatório.');
        }

        return [
            'tema' => $theme,
            'melhoria_id' => !empty($_POST['melhoria_id']) ? (int) $_POST['melhoria_id'] : null,
            'data' => ($_POST['data'] ?? '') !== '' ? $_POST['data'] : date('Y-m-d'),
            'horario' => ($_POST['horario'] ?? '') !== '' ? $_POST['horario'] : date('H:i'),
            'participantes' => trim((string) ($_POST['participantes'] ?? '')),
            'melhorias_discutidas' => trim((string) ($_POST['melhorias_discutidas'] ?? '')),
            'decisoes' => trim((string) ($_POST['decisoes'] ?? '')),
            'proximas_acoes' => trim((string) ($_POST['proximas_acoes'] ?? '')),
            'ata_resumo' => trim((string) ($_POST['ata_resumo'] ?? '')),
        ];
    }
}
