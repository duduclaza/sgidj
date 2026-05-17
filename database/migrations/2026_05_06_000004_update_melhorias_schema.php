<?php

use App\Core\Database;

return new class {
    public function up(PDO $db): void
    {
        // 1. Create melhoria_statuses table
        $db->exec("CREATE TABLE IF NOT EXISTS melhoria_statuses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(50) NOT NULL UNIQUE,
            cor VARCHAR(20) DEFAULT 'bg-slate-500',
            ordem INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Insert default statuses if empty
        $count = $db->query("SELECT COUNT(*) FROM melhoria_statuses")->fetchColumn();
        if ($count == 0) {
            $statuses = [
                ['Em planejamento', 'bg-blue-500'],
                ['Em andamento', 'bg-amber-500'],
                ['Finalizado', 'bg-emerald-500'],
                ['Atrasado', 'bg-red-500']
            ];
            $stmt = $db->prepare("INSERT INTO melhoria_statuses (nome, cor, ordem) VALUES (?, ?, ?)");
            foreach ($statuses as $index => $status) {
                $stmt->execute([$status[0], $status[1], $index]);
            }
        }

        // 2. Add columns to melhorias table
        $db->exec("ALTER TABLE melhorias 
            ADD COLUMN IF NOT EXISTS area_setor VARCHAR(100) AFTER departamento_id,
            ADD COLUMN IF NOT EXISTS outra_area VARCHAR(100) AFTER area_setor,
            ADD COLUMN IF NOT EXISTS responsavel_preenchimento VARCHAR(100) AFTER responsavel_nome,
            ADD COLUMN IF NOT EXISTS o_que TEXT AFTER status,
            ADD COLUMN IF NOT EXISTS quem VARCHAR(100) AFTER o_que,
            ADD COLUMN IF NOT EXISTS onde VARCHAR(100) AFTER quem,
            ADD COLUMN IF NOT EXISTS por_que TEXT AFTER onde,
            ADD COLUMN IF NOT EXISTS quando DATE AFTER por_que,
            ADD COLUMN IF NOT EXISTS como TEXT AFTER quando,
            ADD COLUMN IF NOT EXISTS quanto DECIMAL(10,2) DEFAULT 0.00 AFTER como,
            ADD COLUMN IF NOT EXISTS resultado_esperado TEXT AFTER melhoria_sugerida
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS melhoria_statuses");
        // Usually we don't drop columns in down if we want to keep data, but for completeness:
        // $db->exec("ALTER TABLE melhorias DROP COLUMN area_setor, ...");
    }
};
