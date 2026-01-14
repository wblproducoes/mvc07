<?php

declare(strict_types=1);

/**
 * Classe de conexão com banco de dados usando PDO
 * 
 * Implementa o padrão Singleton para garantir uma única instância
 * de conexão com o banco de dados durante toda a execução.
 * 
 * @package App\Core
 * @author  WBL Produções
 * @version 1.0.0
 * @since   1.0.0
 * 
 * @example
 * ```php
 * $db = Database::getInstance();
 * $conn = $db->getConnection();
 * $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
 * ```
 */
final class Database
{
    /**
     * Instância única da classe (Singleton)
     * 
     * @var self|null
     */
    private static ?self $instance = null;
    
    /**
     * Conexão PDO com o banco de dados
     * 
     * @var PDO
     */
    private readonly PDO $connection;
    
    /**
     * Construtor privado para implementar Singleton
     * 
     * Configura a conexão PDO com as credenciais definidas
     * nas constantes de configuração.
     * 
     * @throws RuntimeException Se houver erro na conexão
     */
    private function __construct()
    {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new RuntimeException("Erro ao conectar ao banco de dados");
        }
    }
    
    /**
     * Retorna a instância única da classe
     * 
     * Cria uma nova instância se ainda não existir,
     * caso contrário retorna a instância existente.
     * 
     * @return self Instância única da classe Database
     * 
     * @example
     * ```php
     * $database = Database::getInstance();
     * ```
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Retorna a conexão PDO ativa
     * 
     * @return PDO Objeto de conexão PDO configurado
     * 
     * @example
     * ```php
     * $conn = Database::getInstance()->getConnection();
     * $stmt = $conn->prepare("SELECT * FROM users");
     * ```
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
    
    /**
     * Previne clonagem da instância
     * 
     * @return void
     */
    private function __clone(): void {}
    
    /**
     * Previne deserialização da instância
     * 
     * @throws RuntimeException Sempre lança exceção
     * @return void
     */
    public function __wakeup(): void
    {
        throw new RuntimeException("Cannot unserialize singleton");
    }
}
