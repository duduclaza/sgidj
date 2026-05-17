<?php

namespace App\Core;

use PDO;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    protected function db(): PDO
    {
        return Database::connection();
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db()->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $statement->execute(['id' => $id]);
        $item = $statement->fetch();
        return $item ?: null;
    }

    public function create(array $data): int
    {
        $data = $this->onlyFillable($data);
        $columns = array_keys($data);
        $placeholders = array_map(fn ($column) => ':' . $column, $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $this->db()->prepare($sql)->execute($data);

        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->onlyFillable($data);
        if ($data === []) {
            return false;
        }

        $assignments = array_map(fn ($column) => "{$column} = :{$column}", array_keys($data));
        $data[$this->primaryKey] = $id;

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :%s',
            $this->table,
            implode(', ', $assignments),
            $this->primaryKey,
            $this->primaryKey
        );

        return $this->db()->prepare($sql)->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db()->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $statement->execute(['id' => $id]);
    }

    protected function onlyFillable(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }
}
