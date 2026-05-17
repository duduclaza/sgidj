<?php
require 'vendor/autoload.php';
App\Core\Env::load('.env');
$db = App\Core\Database::connection();

// Verificar se o status já existe
$stmt = $db->prepare("SELECT id FROM melhoria_statuses WHERE nome = ?");
$stmt->execute(['Aguardando Análise']);
if (!$stmt->fetch()) {
    $db->prepare("INSERT INTO melhoria_statuses (nome, cor, ordem) VALUES (?, ?, ?)")
       ->execute(['Aguardando Análise', 'bg-yellow-400', -1]);
}

echo "Status 'Aguardando Análise' garantido.\n";
