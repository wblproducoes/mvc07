<?php

declare(strict_types=1);

/**
 * Model base com operações CRUD - PHP 8.4+
 */
abstract class Model
{
    protected readonly PDO $db;
    protected string $table = '';
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }
    
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create(array $data): bool
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    public function update(int $id, array $data): bool
    {
        $fields = implode(', ', array_map(
            fn($key) => "{$key} = :{$key}",
            array_keys($data)
        ));
        
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
