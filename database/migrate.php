<?php

use App\Core\Database;
use App\Core\Env;

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
} else {
    require dirname(__DIR__) . '/app/Helpers/functions.php';
    spl_autoload_register(function (string $class): void {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }
        $path = dirname(__DIR__) . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($path)) {
            require $path;
        }
    });
}

Env::load(dirname(__DIR__) . '/.env');

$pdo = Database::connection();
$pdo->exec(
    'CREATE TABLE IF NOT EXISTS migrations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL UNIQUE,
        executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);

$command = $argv[1] ?? 'migrate';
$path = __DIR__ . '/migrations';
$files = glob($path . '/*.php') ?: [];
sort($files);

function migrationInstance(string $file): object
{
    $class = require $file;
    if (is_object($class)) {
        return $class;
    }

    if (is_string($class) && class_exists($class)) {
        return new $class();
    }

    throw new RuntimeException("Migration inválida: {$file}");
}

function transaction(PDO $pdo, callable $callback): void
{
    if (!$pdo->inTransaction()) {
        $pdo->beginTransaction();
    }

    try {
        $callback();
        if ($pdo->inTransaction()) {
            $pdo->commit();
        }
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $exception;
    }
}

function executedMigrations(PDO $pdo): array
{
    return $pdo->query('SELECT migration FROM migrations ORDER BY id ASC')->fetchAll(PDO::FETCH_COLUMN);
}

function runUp(PDO $pdo, string $file): void
{
    $name = basename($file);
    $migration = migrationInstance($file);
    transaction($pdo, function () use ($pdo, $migration, $name) {
        $migration->up($pdo);
        $statement = $pdo->prepare('INSERT INTO migrations (migration) VALUES (:migration)');
        $statement->execute(['migration' => $name]);
    });
    echo "Migrated: {$name}\n";
}

function runDown(PDO $pdo, string $file): void
{
    $name = basename($file);
    $migration = migrationInstance($file);
    transaction($pdo, function () use ($pdo, $migration, $name) {
        $migration->down($pdo);
        $statement = $pdo->prepare('DELETE FROM migrations WHERE migration = :migration');
        $statement->execute(['migration' => $name]);
    });
    echo "Rolled back: {$name}\n";
}

try {
    if ($command === 'rollback') {
        $last = $pdo->query('SELECT migration FROM migrations ORDER BY id DESC LIMIT 1')->fetchColumn();
        if (!$last) {
            echo "Nenhuma migration para desfazer.\n";
            exit(0);
        }

        $file = $path . '/' . $last;
        if (!is_file($file)) {
            throw new RuntimeException("Arquivo da migration não encontrado: {$last}");
        }
        runDown($pdo, $file);
        exit(0);
    }

    if ($command === 'reset') {
        $executed = executedMigrations($pdo);
        for ($i = count($executed) - 1; $i >= 0; $i--) {
            $file = $path . '/' . $executed[$i];
            if (is_file($file)) {
                runDown($pdo, $file);
            }
        }
        foreach ($files as $file) {
            runUp($pdo, $file);
        }
        echo "Banco recriado com sucesso.\n";
        exit(0);
    }

    $executed = executedMigrations($pdo);
    $pending = array_filter($files, fn ($file) => !in_array(basename($file), $executed, true));
    if ($pending === []) {
        echo "Nenhuma migration pendente.\n";
        exit(0);
    }

    foreach ($pending as $file) {
        runUp($pdo, $file);
    }
    echo "Migrations executadas com sucesso.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, "Erro: {$exception->getMessage()}\n");
    exit(1);
}
