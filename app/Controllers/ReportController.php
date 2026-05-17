<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Department;
use App\Models\Improvement;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function index(): void
    {
        if (!Auth::can(['super_admin', 'admin', 'usuario'], 'relatorios')) {
            http_response_code(403);
            require base_path('views/errors/403.php');
            return;
        }

        $filters = $this->filters();
        $rows = (new Improvement())->report($filters);

        $this->view('relatorios/index', [
            'title' => 'Relatórios',
            'rows' => $rows,
            'filters' => $filters,
            'departments' => (new Department())->active(),
            'statuses' => (new ImprovementController())->statuses,
        ]);
    }

    public function export(): void
    {
        if (!Auth::can(['super_admin', 'admin', 'usuario'], 'relatorios')) {
            http_response_code(403);
            require base_path('views/errors/403.php');
            return;
        }

        $filters = $this->filters();
        $rows = (new Improvement())->report($filters);
        $service = new ReportService();
        $format = $_GET['format'] ?? 'csv';

        if ($format === 'pdf') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="relatorio-melhorias.pdf"');
            echo $service->pdf($rows, $filters);
            return;
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="relatorio-melhorias.csv"');
        echo "\xEF\xBB\xBF" . $service->csv($rows);
    }

    private function filters(): array
    {
        return [
            'status' => $_GET['status'] ?? '',
            'departamento_id' => $_GET['departamento_id'] ?? '',
            'responsavel_nome' => $_GET['responsavel_nome'] ?? '',
            'inicio' => $_GET['inicio'] ?? '',
            'fim' => $_GET['fim'] ?? '',
        ];
    }
}
