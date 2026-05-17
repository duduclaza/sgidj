<?php

use App\Core\Database;

return new class {
    public function up(): void
    {
        $db = Database::connection();

        // 1. Alterar a coluna status para VARCHAR para suportar status dinâmicos
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Em planejamento'");

        // 2. Expandir campos de texto do 5W2H para evitar truncamento
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN quem VARCHAR(255) NULL");
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN onde VARCHAR(255) NULL");
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN responsavel_nome VARCHAR(255) NULL");
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN responsavel_preenchimento VARCHAR(255) NULL");

        // 3. Garantir que os campos de acompanhamento sejam TEXT
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN descricao_problema TEXT NULL");
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN melhoria_sugerida TEXT NULL");
        $db->exec("ALTER TABLE melhorias MODIFY COLUMN resultado_esperado TEXT NULL");
        
        // 4. Adicionar colunas se não existirem (garantia extra)
        $cols = $db->query("DESCRIBE melhorias")->fetchAll(PDO::FETCH_COLUMN);
        
        if (!in_array('departamento_id', $cols)) {
            $db->exec("ALTER TABLE melhorias ADD COLUMN departamento_id INT UNSIGNED NULL AFTER titulo");
        }
    }

    public function down(): void
    {
        // Reverter para o estado anterior se necessário (opcional em migrations de ajuste)
    }
};
