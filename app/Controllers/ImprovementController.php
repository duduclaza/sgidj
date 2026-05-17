<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Improvement;
use App\Models\ImprovementStatus;
use App\Models\Meeting;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use App\Services\UploadService;

class ImprovementController extends Controller
{
    public array $priorities = ['Baixa', 'Média', 'Alta', 'Crítica'];

    public function index(): void
    {
        $filters = [
            'q' => $_GET['q'] ?? '',
            'status' => $_GET['status'] ?? '',
            'prioridade' => $_GET['prioridade'] ?? '',
            'departamento_id' => $_GET['departamento_id'] ?? '',
            'responsavel_nome' => $_GET['responsavel_nome'] ?? '',
        ];

        $this->view('melhorias/index', [
            'title' => 'Melhorias',
            'improvements' => (new Improvement())->list($filters),
            'departments' => (new Department())->active(),
            'filters' => $filters,
            'statuses' => (new ImprovementStatus())->allOrdered(),
            'priorities' => $this->priorities,
        ]);
    }

    public function create(): void
    {
        if (!Auth::can(['admin', 'usuario'], 'criar_melhoria')) {
            http_response_code(403);
            require base_path('views/errors/403.php');
            return;
        }

        $this->view('melhorias/form', $this->formData('Nova melhoria'));
    }

    public function store(): void
    {
        verify_csrf();
        if (!Auth::can(['admin', 'usuario'], 'criar_melhoria')) {
            $this->backWithError('Você não tem permissão para cadastrar melhorias.');
        }

        $data = $this->payload(true);
        $data['criado_por'] = Auth::id();
        $data['data_abertura'] = $data['data_abertura'] ?: date('Y-m-d');
        $created = (new Improvement())->createWithTicket($data);
        $id = $created['id'];

        // Process evidence file uploads
        $this->processEvidenceUploads((int) $id);

        AuditLogger::log('criação', 'melhorias', $id, ['titulo' => $data['titulo'], 'ticket' => $created['ticket']]);

        flash('success', 'Melhoria cadastrada com o ticket ' . $created['ticket'] . '.');
        redirect('/melhorias/' . $id);
    }

    public function show(string $id): void
    {
        $improvement = (new Improvement())->findWithRelations((int) $id);
        if (!$improvement) {
            flash('error', 'Melhoria não encontrada.');
            redirect('/melhorias');
        }

        $this->view('melhorias/show', [
            'title' => $improvement['titulo'],
            'improvement' => $improvement,
            'comments' => (new Comment())->byImprovement((int) $id),
            'attachments' => (new Attachment())->byImprovement((int) $id),
            'meetings' => (new Meeting())->byImprovement((int) $id),
        ]);
    }

    public function edit(string $id): void
    {
        $improvement = (new Improvement())->find((int) $id);
        if (!$improvement) {
            flash('error', 'Melhoria não encontrada.');
            redirect('/melhorias');
        }

        $this->view('melhorias/form', $this->formData('Editar melhoria', $improvement));
    }

    public function update(string $id): void
    {
        verify_csrf();
        $model = new Improvement();
        $before = $model->find((int) $id);
        $data = $this->payload(false);
        $model->update((int) $id, $data);

        // Process evidence file uploads
        $this->processEvidenceUploads((int) $id);

        AuditLogger::log('edição', 'melhorias', (int) $id, ['status' => $data['status']]);
        if ($before && $before['status'] !== $data['status'] && !empty($before['criado_por'])) {
            (new NotificationService())->create((int) $before['criado_por'], 'Status atualizado', "{$before['titulo']} agora está como {$data['status']}", 'status', '/melhorias/' . $id);
        }

        flash('success', 'Melhoria atualizada.');
        redirect('/melhorias/' . $id);
    }

    public function updateStatus(string $id): void
    {
        verify_csrf();
        if (!Auth::can('admin')) {
            $this->backWithError('Acesso negado.');
        }

        $status = $_POST['status'] ?? '';
        if ($status === '') {
            $this->backWithError('Status inválido.');
        }

        $model = new Improvement();
        $before = $model->find((int) $id);
        $model->update((int) $id, ['status' => $status]);

        AuditLogger::log('edição', 'melhorias', (int) $id, ['status' => $status, 'tipo' => 'status_inline']);
        
        if ($before && $before['status'] !== $status && !empty($before['criado_por'])) {
            (new NotificationService())->create((int) $before['criado_por'], 'Status atualizado', "{$before['titulo']} agora está como {$status}", 'status', '/melhorias/' . $id);
        }

        flash('success', 'Status atualizado com sucesso.');
        $this->back();
    }

    public function destroy(string $id): void
    {
        verify_csrf();
        (new Improvement())->delete((int) $id);
        AuditLogger::log('exclusão', 'melhorias', (int) $id);
        flash('success', 'Melhoria removida.');
        redirect('/melhorias');
    }

    private function formData(string $title, ?array $improvement = null): array
    {
        return [
            'title' => $title,
            'improvement' => $improvement,
            'departments' => (new Department())->active(),
            'statuses' => (new ImprovementStatus())->allOrdered(),
            'priorities' => $this->priorities,
        ];
    }

    private function payload(bool $creating = false): array
    {
        $title = trim((string) ($_POST['titulo'] ?? ''));
        if ($title === '') {
            $this->backWithError('O título da melhoria é obrigatório.');
        }

        $data = [
            'titulo' => $title,
            'departamento_id' => $_POST['departamento_id'] !== '' ? (int) $_POST['departamento_id'] : null,
            'area_setor' => (string) ($_POST['area_setor'] ?? ''),
            'outra_area' => trim((string) ($_POST['outra_area'] ?? '')),
            'descricao_problema' => trim((string) ($_POST['descricao_problema'] ?? '')),
            'melhoria_sugerida' => trim((string) ($_POST['melhoria_sugerida'] ?? '')),
            'resultado_esperado' => trim((string) ($_POST['resultado_esperado'] ?? '')),
            'prioridade' => (string) ($_POST['prioridade'] ?? 'Média'),
            'status' => (string) ($_POST['status'] ?? 'Em planejamento'),
            'responsavel_id' => null,
            'responsavel_nome' => trim((string) ($_POST['responsavel_nome'] ?? '')),
            'responsavel_preenchimento' => trim((string) ($_POST['responsavel_preenchimento'] ?? '')),
            'o_que' => trim((string) ($_POST['o_que'] ?? '')),
            'quem' => trim((string) ($_POST['quem'] ?? '')),
            'onde' => trim((string) ($_POST['onde'] ?? '')),
            'por_que' => trim((string) ($_POST['por_que'] ?? '')),
            'quando' => ($_POST['quando'] ?? '') !== '' ? $_POST['quando'] : null,
            'como' => trim((string) ($_POST['como'] ?? '')),
            'quanto' => (float) str_replace(['R$', '.', ','], ['', '', '.'], $_POST['quanto'] ?? '0'),
            'prazo' => null,
            'ganho_estimado' => 0,
            'data_abertura' => ($_POST['data_abertura'] ?? '') !== '' ? $_POST['data_abertura'] : null,
            'data_conclusao' => null,
            'causa_raiz' => trim((string) ($_POST['causa_raiz'] ?? '')),
            'observacoes' => trim((string) ($_POST['observacoes'] ?? '')),
        ];

        return $data;
    }

    private function processEvidenceUploads(int $improvementId): void
    {
        $files = $_FILES['evidencias'] ?? null;
        if (!$files || empty($files['name'][0])) {
            return;
        }

        $uploader = new UploadService();
        $attachmentModel = new Attachment();
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }

            $singleFile = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];

            try {
                $uploaded = $uploader->store($singleFile);
                $attachmentModel->create($uploaded + [
                    'melhoria_id' => $improvementId,
                    'usuario_id'  => Auth::id(),
                ]);
            } catch (\Throwable $e) {
                // Skip failed uploads silently
            }
        }
    }

    public function posters(): void
    {
        $this->view('admin/cartazes', [
            'title' => 'Cartazes de Engajamento'
        ]);
    }
}
