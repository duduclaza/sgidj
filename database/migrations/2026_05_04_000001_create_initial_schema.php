<?php

class CreateInitialSchema
{
    public function up(PDO $pdo): void
    {
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS usuarios (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(150) NOT NULL,
                email VARCHAR(190) NOT NULL UNIQUE,
                senha VARCHAR(255) NOT NULL,
                perfil ENUM('super_admin', 'admin', 'usuario') NOT NULL DEFAULT 'usuario',
                status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
                permissoes LONGTEXT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_usuarios_perfil (perfil),
                INDEX idx_usuarios_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS departamentos (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(150) NOT NULL,
                responsavel_id INT UNSIGNED NULL,
                status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_departamentos_status (status),
                CONSTRAINT fk_departamentos_responsavel FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS melhorias (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                ticket VARCHAR(24) NULL,
                titulo VARCHAR(190) NOT NULL,
                departamento_id INT UNSIGNED NULL,
                descricao_problema TEXT NULL,
                melhoria_sugerida TEXT NULL,
                prioridade ENUM('Baixa', 'Média', 'Alta', 'Crítica') NOT NULL DEFAULT 'Média',
                status ENUM('Aberta', 'Em análise', 'Aprovada', 'Em implantação', 'Concluída', 'Cancelada') NOT NULL DEFAULT 'Aberta',
                responsavel_id INT UNSIGNED NULL,
                responsavel_nome VARCHAR(150) NULL,
                prazo DATE NULL,
                ganho_estimado DECIMAL(14,2) NOT NULL DEFAULT 0,
                data_abertura DATE NOT NULL,
                data_conclusao DATE NULL,
                causa_raiz TEXT NULL,
                observacoes TEXT NULL,
                criado_por INT UNSIGNED NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY idx_melhorias_ticket (ticket),
                INDEX idx_melhorias_status (status),
                INDEX idx_melhorias_departamento (departamento_id),
                INDEX idx_melhorias_responsavel (responsavel_id),
                INDEX idx_melhorias_prazo (prazo),
                CONSTRAINT fk_melhorias_departamento FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE SET NULL,
                CONSTRAINT fk_melhorias_responsavel FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
                CONSTRAINT fk_melhorias_criador FOREIGN KEY (criado_por) REFERENCES usuarios(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS reunioes (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tema VARCHAR(190) NOT NULL,
                data DATE NOT NULL,
                horario TIME NOT NULL,
                participantes TEXT NULL,
                melhorias_discutidas TEXT NULL,
                decisoes TEXT NULL,
                proximas_acoes TEXT NULL,
                ata_resumo TEXT NULL,
                criado_por INT UNSIGNED NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_reunioes_data (data),
                CONSTRAINT fk_reunioes_criador FOREIGN KEY (criado_por) REFERENCES usuarios(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS pdca (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                melhoria_id INT UNSIGNED NOT NULL UNIQUE,
                plan_text TEXT NULL,
                plan_status ENUM('Pendente', 'Em andamento', 'Concluída', 'Bloqueada') NOT NULL DEFAULT 'Pendente',
                plan_responsavel_id INT UNSIGNED NULL,
                plan_prazo DATE NULL,
                do_text TEXT NULL,
                do_status ENUM('Pendente', 'Em andamento', 'Concluída', 'Bloqueada') NOT NULL DEFAULT 'Pendente',
                do_responsavel_id INT UNSIGNED NULL,
                do_prazo DATE NULL,
                check_text TEXT NULL,
                check_status ENUM('Pendente', 'Em andamento', 'Concluída', 'Bloqueada') NOT NULL DEFAULT 'Pendente',
                check_responsavel_id INT UNSIGNED NULL,
                check_prazo DATE NULL,
                act_text TEXT NULL,
                act_status ENUM('Pendente', 'Em andamento', 'Concluída', 'Bloqueada') NOT NULL DEFAULT 'Pendente',
                act_responsavel_id INT UNSIGNED NULL,
                act_prazo DATE NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_pdca_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE,
                CONSTRAINT fk_pdca_plan_user FOREIGN KEY (plan_responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
                CONSTRAINT fk_pdca_do_user FOREIGN KEY (do_responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
                CONSTRAINT fk_pdca_check_user FOREIGN KEY (check_responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
                CONSTRAINT fk_pdca_act_user FOREIGN KEY (act_responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS swot (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                melhoria_id INT UNSIGNED NOT NULL UNIQUE,
                forcas TEXT NULL,
                fraquezas TEXT NULL,
                oportunidades TEXT NULL,
                ameacas TEXT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_swot_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS planos_5w2h (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                melhoria_id INT UNSIGNED NOT NULL UNIQUE,
                what_text TEXT NULL,
                why_text TEXT NULL,
                where_text TEXT NULL,
                when_text TEXT NULL,
                who_text TEXT NULL,
                how_text TEXT NULL,
                how_much VARCHAR(120) NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_5w2h_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS comentarios (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                melhoria_id INT UNSIGNED NOT NULL,
                usuario_id INT UNSIGNED NOT NULL,
                comentario TEXT NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_comentarios_melhoria (melhoria_id),
                CONSTRAINT fk_comentarios_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE,
                CONSTRAINT fk_comentarios_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS anexos (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                melhoria_id INT UNSIGNED NOT NULL,
                usuario_id INT UNSIGNED NOT NULL,
                nome_original VARCHAR(255) NOT NULL,
                caminho VARCHAR(255) NOT NULL,
                mime_type VARCHAR(120) NOT NULL,
                tamanho INT UNSIGNED NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_anexos_melhoria (melhoria_id),
                CONSTRAINT fk_anexos_melhoria FOREIGN KEY (melhoria_id) REFERENCES melhorias(id) ON DELETE CASCADE,
                CONSTRAINT fk_anexos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS notificacoes (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT UNSIGNED NOT NULL,
                titulo VARCHAR(150) NOT NULL,
                mensagem TEXT NULL,
                tipo VARCHAR(50) NOT NULL DEFAULT 'info',
                lida TINYINT(1) NOT NULL DEFAULT 0,
                link VARCHAR(255) NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_notificacoes_usuario_lida (usuario_id, lida),
                CONSTRAINT fk_notificacoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS logs_auditoria (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT UNSIGNED NULL,
                acao VARCHAR(120) NOT NULL,
                tabela VARCHAR(120) NOT NULL,
                registro_id INT UNSIGNED NULL,
                detalhes TEXT NULL,
                ip VARCHAR(45) NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_logs_usuario (usuario_id),
                INDEX idx_logs_acao (acao),
                CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $this->seed($pdo);
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        foreach ([
            'logs_auditoria',
            'notificacoes',
            'anexos',
            'comentarios',
            'planos_5w2h',
            'swot',
            'pdca',
            'reunioes',
            'melhorias',
            'departamentos',
            'usuarios',
        ] as $table) {
            $pdo->exec("DROP TABLE IF EXISTS {$table}");
        }
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    }

    private function seed(PDO $pdo): void
    {
        $superAdminName = env('SUPER_ADMIN_NAME', 'Super Admin');
        $superAdminEmail = env('SUPER_ADMIN_EMAIL', '');
        $superAdminPassword = env('SUPER_ADMIN_PASSWORD', '');

        if ($superAdminEmail === '' || $superAdminPassword === '') {
            throw new RuntimeException('Configure SUPER_ADMIN_EMAIL e SUPER_ADMIN_PASSWORD no .env antes de executar as migrations.');
        }

        $statement = $pdo->prepare(
            "INSERT INTO usuarios (nome, email, senha, perfil, status, permissoes)
             SELECT :nome, :email, :senha, 'super_admin', 'ativo', :permissoes
             WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE email = :email_check)"
        );
        $statement->execute([
            'nome' => $superAdminName,
            'email' => $superAdminEmail,
            'email_check' => $superAdminEmail,
            'senha' => password_hash($superAdminPassword, PASSWORD_DEFAULT),
            'permissoes' => json_encode(['criar_melhoria', 'comentar', 'anexar', 'relatorios', 'ia']),
        ]);

        $pdo->exec(
            "INSERT INTO departamentos (nome, status)
             SELECT 'Operações', 'ativo'
             WHERE NOT EXISTS (SELECT 1 FROM departamentos WHERE nome = 'Operações')"
        );
        $pdo->exec(
            "INSERT INTO departamentos (nome, status)
             SELECT 'Qualidade', 'ativo'
             WHERE NOT EXISTS (SELECT 1 FROM departamentos WHERE nome = 'Qualidade')"
        );
    }
}

return CreateInitialSchema::class;
