<?php
// Script temporário de deploy - REMOVER APÓS USO
if (!isset($_GET['token']) || $_GET['token'] !== 'sgidj_deploy_2026') {
    http_response_code(403);
    die('Acesso negado.');
}

$output = [];
$dir = dirname(__DIR__); // pasta raiz do projeto

// Verificar se é um repo git
exec("cd {$dir} && git status 2>&1", $output);
echo "<pre>GIT STATUS:\n" . implode("\n", $output) . "\n\n";

// Fazer o pull
$output = [];
exec("cd {$dir} && git pull origin main 2>&1", $output);
echo "GIT PULL:\n" . implode("\n", $output) . "\n</pre>";

// Verificar .env
echo "<pre>ENV:\n";
$env = file_get_contents($dir . '/.env');
echo "MAIL_HOST=" . (preg_match('/MAIL_HOST=(.+)/', $env, $m) ? $m[1] : 'NAO ENCONTRADO');
echo "\nMAIL_USERNAME=" . (preg_match('/MAIL_USERNAME=(.+)/', $env, $m) ? $m[1] : 'NAO ENCONTRADO');
echo "\n</pre>";
