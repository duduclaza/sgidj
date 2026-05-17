<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'usuarios';
    protected array $fillable = ['nome', 'email', 'senha', 'perfil', 'status', 'permissoes', 'reset_code', 'reset_expires_at'];

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();
        return $user ?: null;
    }

    public function findByResetCode(string $code): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM usuarios WHERE reset_code = :code LIMIT 1');
        $statement->execute(['code' => $code]);
        $user = $statement->fetch();
        return $user ?: null;
    }

    public function list(array $filters = []): array
    {
        $sql = "SELECT * FROM usuarios WHERE email != 'du.claza@gmail.com'";
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= ' AND (nome LIKE :q OR email LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['perfil'])) {
            $sql .= ' AND perfil = :perfil';
            $params['perfil'] = $filters['perfil'];
        }

        $sql .= ' ORDER BY nome ASC';
        $statement = $this->db()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }
}
