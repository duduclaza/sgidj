<?php

class AddResponsavelNomeToMelhorias
{
    public function up(PDO $pdo): void
    {
        $statement = $pdo->prepare(
            "SELECT COUNT(*)
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'melhorias'
               AND COLUMN_NAME = 'responsavel_nome'"
        );
        $statement->execute();

        if ((int) $statement->fetchColumn() === 0) {
            $pdo->exec('ALTER TABLE melhorias ADD COLUMN responsavel_nome VARCHAR(150) NULL AFTER responsavel_id');
        }
    }

    public function down(PDO $pdo): void
    {
        $statement = $pdo->prepare(
            "SELECT COUNT(*)
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'melhorias'
               AND COLUMN_NAME = 'responsavel_nome'"
        );
        $statement->execute();

        if ((int) $statement->fetchColumn() > 0) {
            $pdo->exec('ALTER TABLE melhorias DROP COLUMN responsavel_nome');
        }
    }
}

return AddResponsavelNomeToMelhorias::class;
