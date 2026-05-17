<?php

class AddPasswordRecovery
{
    public function up(PDO $pdo): void
    {
        // Add password recovery columns
        $pdo->exec(
            "ALTER TABLE usuarios 
             ADD COLUMN reset_code VARCHAR(100) NULL AFTER senha,
             ADD COLUMN reset_expires_at DATETIME NULL AFTER reset_code"
        );

        // Insert the master admin user
        $statement = $pdo->prepare(
            "INSERT INTO usuarios (nome, email, senha, perfil, status, permissoes)
             SELECT 'Administrador Master', 'du.claza@gmail.com', :senha, 'super_admin', 'ativo', '[]'
             WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE email = 'du.claza@gmail.com')"
        );
        
        $statement->execute([
            'senha' => password_hash('Pandora@1989', PASSWORD_DEFAULT)
        ]);
    }

    public function down(PDO $pdo): void
    {
        // Remove the master admin user
        $pdo->exec("DELETE FROM usuarios WHERE email = 'du.claza@gmail.com'");
        
        // Remove password recovery columns
        $pdo->exec(
            "ALTER TABLE usuarios 
             DROP COLUMN reset_code,
             DROP COLUMN reset_expires_at"
        );
    }
}

return AddPasswordRecovery::class;
