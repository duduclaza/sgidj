<?php

use App\Core\Auth;

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $base = dirname(__DIR__, 2);
        return $path ? $base . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path) : $base;
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        if ($value === null || $value === '') {
            return $default;
        }

        $lower = strtolower((string) $value);
        return match ($lower) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $value,
        };
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];
        [$file, $item] = array_pad(explode('.', $key, 2), 2, null);
        if (!isset($cache[$file])) {
            $path = base_path("config/{$file}.php");
            $cache[$file] = file_exists($path) ? require $path : [];
        }

        if ($item === null) {
            return $cache[$file] ?? $default;
        }

        return $cache[$file][$item] ?? $default;
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $baseUrl = rtrim((string) config('app.url'), '/');
        $path = '/' . ltrim($path, '/');
        return $baseUrl . $path;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!hash_equals($_SESSION['_csrf'] ?? '', (string) $token)) {
            http_response_code(419);
            exit('Token CSRF inválido. Atualize a página e tente novamente.');
        }
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null): ?string
    {
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return null;
        }

        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('set_old')) {
    function set_old(array $data): void
    {
        $_SESSION['_old'] = $data;
    }
}

if (!function_exists('clear_old')) {
    function clear_old(): void
    {
        unset($_SESSION['_old']);
    }
}

if (!function_exists('money_br')) {
    function money_br(mixed $value): string
    {
        return 'R$ ' . number_format((float) $value, 2, ',', '.');
    }
}

if (!function_exists('date_br')) {
    function date_br(?string $date): string
    {
        if (!$date) {
            return '-';
        }

        return date('d/m/Y', strtotime($date));
    }
}

if (!function_exists('datetime_br')) {
    function datetime_br(?string $date): string
    {
        if (!$date) {
            return '-';
        }

        return date('d/m/Y H:i', strtotime($date));
    }
}

if (!function_exists('is_active')) {
    function is_active(string $prefix): bool
    {
        $current = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        return $prefix === '/' ? $current === '/' : str_starts_with($current, $prefix);
    }
}

if (!function_exists('can')) {
    function can(string|array $roles, ?string $permission = null): bool
    {
        return Auth::can($roles, $permission);
    }
}
