<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\AuditLog;
use Throwable;

class AuditLogger
{
    public static function log(string $action, string $table, ?int $recordId = null, array|string|null $details = null): void
    {
        try {
            (new AuditLog())->create([
                'usuario_id' => Auth::id(),
                'acao' => $action,
                'tabela' => $table,
                'registro_id' => $recordId,
                'detalhes' => is_array($details) ? json_encode($details, JSON_UNESCAPED_UNICODE) : $details,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (Throwable) {
            // Auditoria não deve derrubar a operação principal.
        }
    }
}
