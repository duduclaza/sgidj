<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Services\AuditLogger;

class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $this->authView('auth/login', ['title' => 'Entrar']);
    }

    public function authenticate(): void
    {
        verify_csrf();
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['senha'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            flash('error', 'Informe um e-mail válido e sua senha.');
            set_old($_POST);
            redirect('/login');
        }

        if (!Auth::attempt($email, $password)) {
            flash('error', 'E-mail, senha ou status do usuário inválido.');
            set_old(['email' => $email]);
            redirect('/login');
        }

        AuditLogger::log('login', 'usuarios', Auth::id());
        flash('success', 'Bem-vindo ao sistema.');
        redirect('/dashboard');
    }

    public function forgot(): void
    {
        $this->authView('auth/forgot', ['title' => 'Recuperar senha']);
    }

    public function forgotSend(): void
    {
        verify_csrf();
        $email = trim((string) ($_POST['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Informe um e-mail válido.');
            redirect('/recuperar-senha');
        }

        $userModel = new \App\Models\User();
        $user = $userModel->findByEmail($email);

        if ($user && $user['status'] === 'ativo') {
            $code = bin2hex(random_bytes(16));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $userModel->update($user['id'], [
                'reset_code' => $code,
                'reset_expires_at' => $expiresAt
            ]);

            $resetLink = url("/redefinir-senha/{$code}");
            $html = "<h3>Recuperação de Senha</h3>
                     <p>Olá, " . e($user['nome']) . ".</p>
                     <p>Você solicitou a redefinição de senha.</p>
                     <p>Clique no link abaixo para criar uma nova senha:</p>
                     <p><a href=\"{$resetLink}\">{$resetLink}</a></p>
                     <p>Este link expira em 1 hora.</p>
                     <p>Se você não solicitou, ignore este e-mail.</p>";

            \App\Services\MailService::send($email, 'Recuperação de Senha - SGI ATLAS', $html);
        }

        flash('success', 'Se o e-mail existir e estiver ativo, enviaremos as instruções para redefinição de senha.');
        redirect('/login');
    }

    public function reset(string $code): void
    {
        $userModel = new \App\Models\User();
        $user = $userModel->findByResetCode($code);

        if (!$user || strtotime($user['reset_expires_at']) < time()) {
            flash('error', 'Link de recuperação inválido ou expirado.');
            redirect('/login');
        }

        $this->authView('auth/reset', ['title' => 'Redefinir senha', 'code' => $code]);
    }

    public function resetUpdate(string $code): void
    {
        verify_csrf();
        $password = (string) ($_POST['senha'] ?? '');
        $passwordConfirm = (string) ($_POST['senha_confirmacao'] ?? '');

        if (strlen($password) < 6 || $password !== $passwordConfirm) {
            flash('error', 'A senha deve ter no mínimo 6 caracteres e ser igual à confirmação.');
            redirect("/redefinir-senha/{$code}");
        }

        $userModel = new \App\Models\User();
        $user = $userModel->findByResetCode($code);

        if (!$user || strtotime($user['reset_expires_at']) < time()) {
            flash('error', 'Link de recuperação inválido ou expirado.');
            redirect('/login');
        }

        $userModel->update($user['id'], [
            'senha' => password_hash($password, PASSWORD_DEFAULT),
            'reset_code' => null,
            'reset_expires_at' => null
        ]);

        AuditLogger::log('reset_password', 'usuarios', $user['id']);
        flash('success', 'Sua senha foi redefinida com sucesso. Faça login.');
        redirect('/login');
    }

    public function logout(): void
    {
        verify_csrf();
        AuditLogger::log('logout', 'usuarios', Auth::id());
        Auth::logout();
        flash('success', 'Sessão encerrada com segurança.');
        redirect('/login');
    }
}
