<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Services\AuditLogger;

class UserController extends Controller
{
    private array $profiles = ['super_admin' => 'Super Admin', 'admin' => 'Admin', 'usuario' => 'Usuário/Colaborador'];
    private array $permissions = [
        'criar_melhoria' => 'Cadastrar melhorias',
        'comentar' => 'Comentar em melhorias',
        'anexar' => 'Anexar arquivos',
        'relatorios' => 'Acessar relatórios',
        'ia' => 'Usar agente de IA',
    ];

    public function index(): void
    {
        $filters = ['q' => $_GET['q'] ?? '', 'perfil' => $_GET['perfil'] ?? ''];
        $this->view('usuarios/index', [
            'title' => 'Usuários',
            'users' => (new User())->list($filters),
            'filters' => $filters,
            'profiles' => $this->profiles,
        ]);
    }

    public function create(): void
    {
        $this->view('usuarios/form', [
            'title' => 'Novo usuário',
            'user' => null,
            'profiles' => $this->profiles,
            'permissions' => $this->permissions,
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        $data = $this->payload();
        if ($data['senha'] === '') {
            $this->backWithError('A senha é obrigatória para novos usuários.');
        }
        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);

        $id = (new User())->create($data);
        AuditLogger::log('criação', 'usuarios', $id, ['email' => $data['email'], 'perfil' => $data['perfil']]);
        flash('success', 'Usuário criado com sucesso.');
        redirect('/usuarios');
    }

    public function edit(string $id): void
    {
        $user = (new User())->find((int) $id);
        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            redirect('/usuarios');
        }

        $this->view('usuarios/form', [
            'title' => 'Editar usuário',
            'user' => $user,
            'profiles' => $this->profiles,
            'permissions' => $this->permissions,
        ]);
    }

    public function update(string $id): void
    {
        verify_csrf();
        $data = $this->payload();
        if ($data['senha'] === '') {
            unset($data['senha']);
        } else {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        (new User())->update((int) $id, $data);
        AuditLogger::log('edição', 'usuarios', (int) $id);
        flash('success', 'Usuário atualizado.');
        redirect('/usuarios');
    }

    public function destroy(string $id): void
    {
        verify_csrf();
        (new User())->delete((int) $id);
        AuditLogger::log('exclusão', 'usuarios', (int) $id);
        flash('success', 'Usuário removido.');
        redirect('/usuarios');
    }

    public function toggle(string $id): void
    {
        verify_csrf();
        $model = new User();
        $user = $model->find((int) $id);
        if ($user) {
            $model->update((int) $id, ['status' => $user['status'] === 'ativo' ? 'inativo' : 'ativo']);
            AuditLogger::log('alteração de status', 'usuarios', (int) $id);
        }
        redirect('/usuarios');
    }

    private function payload(): array
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->backWithError('Informe um e-mail válido.');
        }

        $permissions = $_POST['permissoes'] ?? [];
        return [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'email' => $email,
            'senha' => (string) ($_POST['senha'] ?? ''),
            'perfil' => (string) ($_POST['perfil'] ?? 'usuario'),
            'status' => (string) ($_POST['status'] ?? 'ativo'),
            'permissoes' => json_encode(array_values((array) $permissions), JSON_UNESCAPED_UNICODE),
        ];
    }
}
