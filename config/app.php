<?php

return [
    'name' => env('APP_NAME', 'Sistema de Melhoria Contínua'),
    'env' => env('APP_ENV', 'local'),
    'debug' => filter_var(env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim((string) env('APP_URL', 'http://localhost:8000'), '/'),
    'session_name' => env('SESSION_NAME', 'melhoria_session'),
];
