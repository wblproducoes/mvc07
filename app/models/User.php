<?php

declare(strict_types=1);

/**
 * Model de usuário - PHP 8.4+
 */
final class User extends Model
{
    protected string $table = 'users';
    
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function authenticate(string $email, string $password): array|false
    {
        $user = $this->findByEmail($email);
        
        if ($user && Security::verifyPassword($password, $user['password'])) {
            unset($user['password']); // Não retornar senha
            return $user;
        }
        
        return false;
    }
    
    public function register(array $data): bool
    {
        // Verificar se email já existe
        if ($this->findByEmail($data['email'])) {
            return false;
        }
        
        $data['password'] = Security::hashPassword($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    public function updateLastLogin(int $userId): bool
    {
        $sql = "UPDATE {$this->table} SET updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }
}
