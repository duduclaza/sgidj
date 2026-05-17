<?php

use App\Core\Database;

return new class {
    public function up(): void
    {
        $db = Database::connection();

        // 1. Comentários
        try { $db->exec("ALTER TABLE comentarios DROP FOREIGN KEY fk_comentarios_melhoria"); } catch(Exception $e){}
        $db->exec("ALTER TABLE comentarios ADD CONSTRAINT fk_comentarios_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE");

        // 2. Anexos
        try { $db->exec("ALTER TABLE anexos DROP FOREIGN KEY fk_anexos_melhoria"); } catch(Exception $e){}
        $db->exec("ALTER TABLE anexos ADD CONSTRAINT fk_anexos_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE");

        // 3. PDCA
        try { $db->exec("ALTER TABLE pdca DROP FOREIGN KEY fk_pdca_melhoria"); } catch(Exception $e){}
        $db->exec("ALTER TABLE pdca ADD CONSTRAINT fk_pdca_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE");

        // 4. Reuniões
        try { $db->exec("ALTER TABLE reunioes DROP FOREIGN KEY fk_reunioes_melhoria"); } catch(Exception $e){}
        $db->exec("ALTER TABLE reunioes ADD CONSTRAINT fk_reunioes_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE");
    }

    public function down(): void
    {
        // Opcional
    }
};
