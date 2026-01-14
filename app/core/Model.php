<?php

declare(strict_types=1);

/**
 * Model base com operações CRUD
 * 
 * Classe abstrata que fornece métodos básicos de CRUD (Create, Read, Update, Delete)
 * para interação com o banco de dados. Todos os models devem estender esta classe.
 * 
 * @package App\Core
 * @author  WBL Produções
 * @version 1.0.0
 * @since   1.0.0
 * 
 * @example
 * ```php
 * class User extends Model {
 *     protected string $table = 'users';
 * }
 * ```
 */
abstract class Model
{
    /**
     * Conexão PDO com o banco de dados
     * 
     * @var PDO
     */
    protected readonly PDO $db;
    
    /**
     * Nome da tabela no banco de dados
     * 
     * @var string
     */
    protected string $table = '';
    
    /**
     * Construtor do Model
     * 
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Busca todos os registros da tabela
     * 
     * @return array Array de registros
     * 
     * @example
     * ```php
     * $users = $userModel->findAll();
     * ```
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }
    
    /**
     * Busca um registro por ID
     * 
     * @param int $id ID do registro
     * @return array|false Array com dados do registro ou false se não encontrado
     * 
     * @example
     * ```php
     * $user = $userModel->findById(1);
     * if ($user) {
     *     echo $user['name'];
     * }
     * ```
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Cria um novo registro na tabela
     * 
     * @param array $data Array associativo com os dados (campo => valor)
     * @return bool True se criado com sucesso
     * 
     * @example
     * ```php
     * $success = $userModel->create([
     *     'name' => 'João',
     *     'email' => 'joao@example.com'
     * ]);
     * ```
     */
    public function create(array $data): bool
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    /**
     * Atualiza um registro existente
     * 
     * @param int $id ID do registro a ser atualizado
     * @param array $data Array associativo com os dados a atualizar
     * @return bool True se atualizado com sucesso
     * 
     * @example
     * ```php
     * $success = $userModel->update(1, [
     *     'name' => 'João Silva',
     *     'email' => 'joao.silva@example.com'
     * ]);
     * ```
     */
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
    
    /**
     * Deleta um registro por ID
     * 
     * @param int $id ID do registro a ser deletado
     * @return bool True se deletado com sucesso
     * 
     * @example
     * ```php
     * $success = $userModel->delete(1);
     * ```
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Executa uma query SQL customizada
     * 
     * @param string $sql Query SQL com placeholders
     * @param array $params Parâmetros para bind
     * @return PDOStatement Statement executado
     * 
     * @example
     * ```php
     * $stmt = $this->query("SELECT * FROM users WHERE status = ?", ['active']);
     * $users = $stmt->fetchAll();
     * ```
     */
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
