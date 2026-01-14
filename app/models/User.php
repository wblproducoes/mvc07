<?php

declare(strict_types=1);

/**
 * Model de usuário
 * 
 * Gerencia operações relacionadas a usuários incluindo
 * autenticação, registro e consultas específicas.
 * 
 * @package App\Models
 * @author  WBL Produções
 * @version 1.0.0
 * @since   1.0.0
 */
final class User extends Model
{
    /**
     * Nome da tabela no banco de dados
     * 
     * @var string
     */
    protected string $table = 'users';
    
    /**
     * Busca usuário por email
     * 
     * @param string $email Email do usuário
     * @return array|false Dados do usuário ou false se não encontrado
     * 
     * @example
     * ```php
     * $user = $userModel->findByEmail('user@example.com');
     * ```
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * Autentica usuário com email e senha
     * 
     * Verifica se o email existe e se a senha corresponde ao hash armazenado.
     * Remove a senha do array retornado por segurança.
     * 
     * @param string $email Email do usuário
     * @param string $password Senha em texto plano
     * @return array|false Dados do usuário (sem senha) ou false se falhar
     * 
     * @example
     * ```php
     * $user = $userModel->authenticate('user@example.com', 'senha123');
     * if ($user) {
     *     $_SESSION['user_id'] = $user['id'];
     * }
     * ```
     */
    public function authenticate(string $email, string $password): array|false
    {
        $user = $this->findByEmail($email);
        
        if ($user && Security::verifyPassword($password, $user['password'])) {
            unset($user['password']); // Não retornar senha
            return $user;
        }
        
        return false;
    }
    
    /**
     * Registra novo usuário
     * 
     * Verifica se o email já existe, faz hash da senha e cria o registro.
     * 
     * @param array $data Dados do usuário (name, email, password)
     * @return bool True se registrado com sucesso, false se email já existe
     * 
     * @example
     * ```php
     * $success = $userModel->register([
     *     'name' => 'João Silva',
     *     'email' => 'joao@example.com',
     *     'password' => 'senha123'
     * ]);
     * ```
     */
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
    
    /**
     * Atualiza timestamp do último login do usuário
     * 
     * @param int $userId ID do usuário
     * @return bool True se atualizado com sucesso
     * 
     * @example
     * ```php
     * $userModel->updateLastLogin($userId);
     * ```
     */
    public function updateLastLogin(int $userId): bool
    {
        $sql = "UPDATE {$this->table} SET updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }
}
