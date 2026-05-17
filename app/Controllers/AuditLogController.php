<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(): void
    {
        $filters = ['q' => $_GET['q'] ?? ''];
        $this->view('relatorios/logs', [
            'title' => 'Logs de Auditoria',
            'logs' => (new AuditLog())->list($filters),
            'filters' => $filters,
        ]);
    }
}
