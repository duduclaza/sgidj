<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'layouts/app'): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require base_path('views/' . str_replace('.', '/', $view) . '.php');
        $content = ob_get_clean();

        require base_path('views/' . str_replace('.', '/', $layout) . '.php');
        clear_old();
    }

    protected function authView(string $view, array $data = []): void
    {
        $this->view($view, $data, 'layouts/auth');
    }

    protected function backWithError(string $message): never
    {
        flash('error', $message);
        set_old($_POST);
        $this->back();
    }

    protected function back(): never
    {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('/dashboard')));
        exit;
    }
}
