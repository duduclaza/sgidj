<?php

use App\Controllers\AiAgentController;
use App\Controllers\AttachmentController;
use App\Controllers\AuditLogController;
use App\Controllers\AuthController;
use App\Controllers\CommentController;
use App\Controllers\DashboardController;
use App\Controllers\DepartmentController;
use App\Controllers\FiveW2HController;
use App\Controllers\ImprovementController;
use App\Controllers\MeetingController;
use App\Controllers\ImprovementStatusController;
use App\Controllers\NotificationController;
use App\Controllers\PdcaController;
use App\Controllers\PublicImprovementController;
use App\Controllers\ReportController;
use App\Controllers\SwotController;
use App\Controllers\UserController;

// Status Configuration
$router->get('/configuracao/status', [ImprovementStatusController::class, 'index'], ['auth', 'role:admin']);
$router->get('/configuracao/cartazes', [ImprovementController::class, 'posters'], ['auth', 'role:admin']);
$router->post('/configuracao/status', [ImprovementStatusController::class, 'store'], ['auth', 'role:admin']);
$router->post('/configuracao/status/{id}/atualizar', [ImprovementStatusController::class, 'update'], ['auth', 'role:admin']);
$router->post('/configuracao/status/{id}/excluir', [ImprovementStatusController::class, 'destroy'], ['auth', 'role:admin']);

// Public Routes
$router->get('/solicitar', [PublicImprovementController::class, 'requestSimple']);
$router->get('/solicitar-completo', [PublicImprovementController::class, 'requestFull']);
$router->post('/solicitar', [PublicImprovementController::class, 'store']);

$router->get('/melhoria-publica', [PublicImprovementController::class, 'index']);
$router->post('/melhoria-publica/consultar', [PublicImprovementController::class, 'lookup']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/recuperar-senha', [AuthController::class, 'forgot']);
$router->post('/recuperar-senha', [AuthController::class, 'forgotSend']);
$router->get('/redefinir-senha/{code}', [AuthController::class, 'reset']);
$router->post('/redefinir-senha/{code}', [AuthController::class, 'resetUpdate']);
$router->post('/logout', [AuthController::class, 'logout'], ['auth']);

$router->get('/', [DashboardController::class, 'index'], ['auth']);
$router->get('/dashboard', [DashboardController::class, 'index'], ['auth']);

$router->get('/usuarios', [UserController::class, 'index'], ['auth', 'role:super_admin']);
$router->get('/usuarios/novo', [UserController::class, 'create'], ['auth', 'role:super_admin']);
$router->post('/usuarios', [UserController::class, 'store'], ['auth', 'role:super_admin']);
$router->get('/usuarios/{id}/editar', [UserController::class, 'edit'], ['auth', 'role:super_admin']);
$router->post('/usuarios/{id}/atualizar', [UserController::class, 'update'], ['auth', 'role:super_admin']);
$router->post('/usuarios/{id}/excluir', [UserController::class, 'destroy'], ['auth', 'role:super_admin']);
$router->post('/usuarios/{id}/toggle', [UserController::class, 'toggle'], ['auth', 'role:super_admin']);

$router->get('/departamentos', [DepartmentController::class, 'index'], ['auth', 'role:admin']);
$router->get('/departamentos/novo', [DepartmentController::class, 'create'], ['auth', 'role:admin']);
$router->post('/departamentos', [DepartmentController::class, 'store'], ['auth', 'role:admin']);
$router->get('/departamentos/{id}/editar', [DepartmentController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/departamentos/{id}/atualizar', [DepartmentController::class, 'update'], ['auth', 'role:admin']);
$router->post('/departamentos/{id}/excluir', [DepartmentController::class, 'destroy'], ['auth', 'role:admin']);

$router->get('/melhorias', [ImprovementController::class, 'index'], ['auth', 'role:admin|usuario']);
$router->get('/melhorias/nova', [ImprovementController::class, 'create'], ['auth', 'role:admin|usuario']);
$router->post('/melhorias', [ImprovementController::class, 'store'], ['auth', 'role:admin|usuario']);
$router->get('/melhorias/{id}', [ImprovementController::class, 'show'], ['auth', 'role:admin|usuario']);
$router->get('/melhorias/{id}/editar', [ImprovementController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/melhorias/{id}/atualizar', [ImprovementController::class, 'update'], ['auth', 'role:admin']);
$router->post('/melhorias/{id}/status', [ImprovementController::class, 'updateStatus'], ['auth', 'role:admin']);
$router->post('/melhorias/{id}/excluir', [ImprovementController::class, 'destroy'], ['auth', 'role:admin']);

$router->post('/melhorias/{id}/comentarios', [CommentController::class, 'store'], ['auth', 'role:admin|usuario']);
$router->post('/comentarios/{id}/excluir', [CommentController::class, 'destroy'], ['auth', 'role:admin']);
$router->post('/melhorias/{id}/anexos', [AttachmentController::class, 'store'], ['auth', 'role:admin|usuario']);
$router->get('/anexos/{id}/baixar', [AttachmentController::class, 'download'], ['auth', 'role:admin|usuario']);
$router->post('/anexos/{id}/excluir', [AttachmentController::class, 'destroy'], ['auth', 'role:admin']);

$router->get('/reunioes', [MeetingController::class, 'index'], ['auth', 'role:admin']);
$router->get('/reunioes/nova', [MeetingController::class, 'create'], ['auth', 'role:admin']);
$router->post('/reunioes', [MeetingController::class, 'store'], ['auth', 'role:admin']);
$router->get('/reunioes/{id}/editar', [MeetingController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/reunioes/{id}/atualizar', [MeetingController::class, 'update'], ['auth', 'role:admin']);
$router->post('/reunioes/{id}/excluir', [MeetingController::class, 'destroy'], ['auth', 'role:admin']);

$router->get('/pdca', [PdcaController::class, 'index'], ['auth', 'role:admin']);
$router->get('/pdca/{id}/editar', [PdcaController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/pdca/{id}', [PdcaController::class, 'update'], ['auth', 'role:admin']);

$router->get('/swot', [SwotController::class, 'index'], ['auth', 'role:admin']);
$router->get('/swot/{id}/editar', [SwotController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/swot/{id}', [SwotController::class, 'update'], ['auth', 'role:admin']);

$router->get('/5w2h', [FiveW2HController::class, 'index'], ['auth', 'role:admin']);
$router->get('/5w2h/{id}/editar', [FiveW2HController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/5w2h/{id}', [FiveW2HController::class, 'update'], ['auth', 'role:admin']);

$router->get('/notificacoes', [NotificationController::class, 'index'], ['auth']);
$router->post('/notificacoes/{id}/lida', [NotificationController::class, 'markRead'], ['auth']);

$router->get('/relatorios', [ReportController::class, 'index'], ['auth', 'role:admin|usuario']);
$router->get('/relatorios/exportar', [ReportController::class, 'export'], ['auth', 'role:admin|usuario']);
$router->get('/logs-auditoria', [AuditLogController::class, 'index'], ['auth', 'role:super_admin']);

$router->get('/ia', [AiAgentController::class, 'index'], ['auth', 'role:admin|usuario']);
$router->post('/ia/sugerir', [AiAgentController::class, 'assist'], ['auth', 'role:admin|usuario']);
