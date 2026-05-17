<?php

class AddTicketToMelhorias
{
    public function up(PDO $pdo): void
    {
        if (!$this->columnExists($pdo, 'melhorias', 'ticket')) {
            $pdo->exec('ALTER TABLE melhorias ADD COLUMN ticket VARCHAR(24) NULL AFTER id');
        }

        $rows = $pdo->query(
            "SELECT id, data_abertura
             FROM melhorias
             WHERE ticket IS NULL OR ticket = ''
             ORDER BY id ASC"
        )->fetchAll(PDO::FETCH_ASSOC);

        $statement = $pdo->prepare('UPDATE melhorias SET ticket = :ticket WHERE id = :id');
        foreach ($rows as $row) {
            $statement->execute([
                'ticket' => $this->ticketFor((int) $row['id'], $row['data_abertura'] ?? null),
                'id' => (int) $row['id'],
            ]);
        }

        if (!$this->indexExists($pdo, 'melhorias', 'idx_melhorias_ticket')) {
            $pdo->exec('ALTER TABLE melhorias ADD UNIQUE INDEX idx_melhorias_ticket (ticket)');
        }
    }

    public function down(PDO $pdo): void
    {
        if ($this->indexExists($pdo, 'melhorias', 'idx_melhorias_ticket')) {
            $pdo->exec('ALTER TABLE melhorias DROP INDEX idx_melhorias_ticket');
        }

        if ($this->columnExists($pdo, 'melhorias', 'ticket')) {
            $pdo->exec('ALTER TABLE melhorias DROP COLUMN ticket');
        }
    }

    private function columnExists(PDO $pdo, string $table, string $column): bool
    {
        $statement = $pdo->prepare(
            "SELECT COUNT(*)
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = :table_name
               AND COLUMN_NAME = :column_name"
        );
        $statement->execute(['table_name' => $table, 'column_name' => $column]);

        return (int) $statement->fetchColumn() > 0;
    }

    private function indexExists(PDO $pdo, string $table, string $index): bool
    {
        $statement = $pdo->prepare(
            "SELECT COUNT(*)
             FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = :table_name
               AND INDEX_NAME = :index_name"
        );
        $statement->execute(['table_name' => $table, 'index_name' => $index]);

        return (int) $statement->fetchColumn() > 0;
    }

    private function ticketFor(int $id, ?string $date): string
    {
        $timestamp = $date ? strtotime($date) : false;
        $year = $timestamp ? date('Y', $timestamp) : date('Y');

        return 'MEL-' . $year . '-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }
}

return AddTicketToMelhorias::class;
