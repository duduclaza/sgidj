<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function user(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }

        static $user = null;
        if ($user === null) {
            $user = (new User())->find((int) $_SESSION['user_id']);
        }

        return $user ?: null;
    }

    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findByEmail($email);
        if (!$user || $user['status'] !== 'ativo' || !password_verify($password, $user['senha'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }

    public static function can(string|array $roles, ?string $permission = null): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        $allowedRoles = (array) $roles;
        if (in_array($user['perfil'], ['super_admin'], true)) {
            return true;
        }

        if (!in_array($user['perfil'], $allowedRoles, true)) {
            return false;
        }

        if ($permission === null || $user['perfil'] === 'admin') {
            return true;
        }

        $permissions = json_decode((string) ($user['permissoes'] ?? '[]'), true) ?: [];
        return in_array($permission, $permissions, true);
    }
}
