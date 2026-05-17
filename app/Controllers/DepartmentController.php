<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Department;
use App\Models\User;
use App\Services\AuditLogger;

class DepartmentController extends Controller
{
    public function index(): void
    {
        $filters = ['q' => $_GET['q'] ?? '', 'status' => $_GET['status'] ?? ''];
        $this->view('departamentos/index', [
            'title' => 'Departamentos',
            'departments' => (new Department())->list($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        $this->view('departamentos/form', [
            'title' => 'Novo departamento',
            'department' => null,
            'users' => (new User())->list(),
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        $id = (new Department())->create($this->payload());
        AuditLogger::log('criação', 'departamentos', $id);
        flash('success', 'Departamento criado.');
        redirect('/departamentos');
    }

    public function edit(string $id): void
    {
        $department = (new Department())->find((int) $id);
        if (!$department) {
            flash('error', 'Departamento não encontrado.');
            redirect('/departamentos');
        }

        $this->view('departamentos/form', [
            'title' => 'Editar departamento',
            'department' => $department,
            'users' => (new User())->list(),
        ]);
    }

    public function update(string $id): void
    {
        verify_csrf();
        (new Department())->update((int) $id, $this->payload());
        AuditLogger::log('edição', 'departamentos', (int) $id);
        flash('success', 'Departamento atualizado.');
        redirect('/departamentos');
    }

    public function destroy(string $id): void
    {
        verify_csrf();
        (new Department())->delete((int) $id);
        AuditLogger::log('exclusão', 'departamentos', (int) $id);
        flash('success', 'Departamento removido.');
        redirect('/departamentos');
    }

    private function payload(): array
    {
        $name = trim((string) ($_POST['nome'] ?? ''));
        if ($name === '') {
            $this->backWithError('O nome do departamento é obrigatório.');
        }

        return [
            'nome' => $name,
            'responsavel_id' => ($_POST['responsavel_id'] ?? '') !== '' ? (int) $_POST['responsavel_id'] : null,
            'status' => (string) ($_POST['status'] ?? 'ativo'),
        ];
    }
}
