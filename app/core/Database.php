<?php

declare(strict_types=1);

/**
 * Classe de conexão com banco de dados usando PDO - PHP 8.4+
 * Padrão Singleton
 */
final class Database
{
    private static ?self $instance = null;
    private readonly PDO $connection;
    
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
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO
    {
        return $this->connection;
    }
    
    private function __clone(): void {}
    
    public function __wakeup(): void
    {
        throw new RuntimeException("Cannot unserialize singleton");
    }
}
