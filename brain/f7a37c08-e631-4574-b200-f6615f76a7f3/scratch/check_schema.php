<?php
require 'vendor/autoload.php';
App\Core\Env::load('.env');
$db = App\Core\Database::connection();
$res = $db->query("DESCRIBE melhorias");
$schema = $res->fetchAll(PDO::FETCH_ASSOC);
foreach ($schema as $col) {
    echo "{$col['Field']} - {$col['Type']} - NULL: {$col['Null']}\n";
}
