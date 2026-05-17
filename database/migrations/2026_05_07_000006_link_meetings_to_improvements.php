<?php

use App\Core\Database;

return new class {
    public function up(): void
    {
        $db = Database::connection();
        
        $cols = $db->query("DESCRIBE reunioes")->fetchAll(PDO::FETCH_COLUMN);
        
        if (!in_array('melhoria_id', $cols)) {
            $db->exec("ALTER TABLE reunioes ADD COLUMN melhoria_id INT UNSIGNED NULL AFTER id");
            $db->exec("ALTER TABLE reunioes ADD CONSTRAINT fk_reunioes_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE SET NULL");
        }
    }

    public function down(): void
    {
        $db = Database::connection();
        $db->exec("ALTER TABLE reunioes DROP FOREIGN KEY fk_reunioes_melhoria");
        $db->exec("ALTER TABLE reunioes DROP COLUMN melhoria_id");
    }
};
