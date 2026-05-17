<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Department;
use App\Models\Improvement;
use App\Services\AuditLogger;

class PublicImprovementController extends Controller
{
    private array $priorities = ['Baixa', 'Média', 'Alta', 'Crítica'];

    public function index(): void
    {
        $model = new Improvement();
        $ticket = (string) ($_GET['ticket'] ?? '');
        $lookup = null;
        $notFound = false;

        if ($ticket !== '') {
            $lookup = $model->findByTicket($ticket);
            $notFound = $lookup === null;
        }

        $this->view('public/consulta', [
            'title' => 'Consultar melhoria',
            'lookup' => $lookup,
            'lookupTicket' => $ticket,
            'notFound' => $notFound,
        ], 'layouts/public');
    }

    public function requestSimple(): void
    {
        $this->renderForm('simple');
    }

    public function requestFull(): void
    {
        $this->renderForm('full');
    }

    public function store(): void
    {
        verify_csrf();

        $title = trim((string) ($_POST['titulo'] ?? ''));
        $name = trim((string) ($_POST['responsavel_nome'] ?? ''));
        $problem = trim((string) ($_POST['descricao_problema'] ?? ''));

        if ($title === '' || $name === '' || $problem === '') {
            $this->publicBackWithError('Preencha título, seu nome e descrição do problema para enviar a melhoria.');
        }

        $departmentId = ($_POST['departamento_id'] ?? '') !== '' ? (int) $_POST['departamento_id'] : null;
        
        $model = new Improvement();
        $created = $model->createWithTicket([
            'titulo' => $title,
            'departamento_id' => $departmentId,
            'descricao_problema' => $problem,
            'melhoria_sugerida' => trim((string) ($_POST['melhoria_sugerida'] ?? '')),
            'resultado_esperado' => trim((string) ($_POST['resultado_esperado'] ?? '')),
            'prioridade' => 'Média',
            'status' => 'Aguardando Análise',
            'responsavel_id' => null,
            'responsavel_nome' => $name,
            'responsavel_preenchimento' => $name,
            'o_que' => trim((string) ($_POST['o_que'] ?? '')),
            'quem' => trim((string) ($_POST['quem'] ?? '')),
            'onde' => trim((string) ($_POST['onde'] ?? '')),
            'por_que' => trim((string) ($_POST['por_que'] ?? '')),
            'quando' => ($_POST['quando'] ?? '') !== '' ? $_POST['quando'] : null,
            'como' => trim((string) ($_POST['como'] ?? '')),
            'quanto' => (float) str_replace(['R$', '.', ','], ['', '', '.'], $_POST['quanto'] ?? '0'),
            'prazo' => null,
            'ganho_estimado' => 0,
            'data_abertura' => date('Y-m-d'),
            'data_conclusao' => null,
            'causa_raiz' => '',
            'observacoes' => trim((string) ($_POST['observacoes'] ?? '')),
            'criado_por' => null,
        ]);

        AuditLogger::log('criação pública', 'melhorias', $created['id'], [
            'titulo' => $title,
            'ticket' => $created['ticket'],
        ]);

        flash('success', 'Melhoria enviada com sucesso! Guarde seu ticket para consulta: ' . $created['ticket']);
        redirect('/melhoria-publica?ticket=' . urlencode($created['ticket']));
    }

    public function lookup(): void
    {
        verify_csrf();
        $ticket = trim((string) ($_POST['ticket'] ?? ''));

        if ($ticket === '') {
            flash('error', 'Digite o ticket para consultar a melhoria.');
            redirect('/melhoria-publica');
        }

        redirect('/melhoria-publica?ticket=' . urlencode($ticket));
    }

    private function renderForm(string $type): void
    {
        $this->view('public/request', [
            'title' => $type === 'simple' ? 'Formulário de Requisição' : 'Formulário de Requisição + Ação',
            'departments' => (new Department())->active(),
            'priorities' => $this->priorities,
            'type' => $type
        ], 'layouts/public');
    }

    private function publicBackWithError(string $message): never
    {
        flash('error', $message);
        set_old($_POST);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('/solicitar')));
        exit;
    }
}
