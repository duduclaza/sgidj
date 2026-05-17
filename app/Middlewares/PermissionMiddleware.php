<?php

namespace App\Middlewares;

use App\Core\Auth;

class PermissionMiddleware
{
    public function handle(array $roles): void
    {
        if (!Auth::can($roles)) {
            http_response_code(403);
            require base_path('views/errors/403.php');
            exit;
        }
    }
}
