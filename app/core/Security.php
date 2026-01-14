<?php

declare(strict_types=1);

/**
 * Classe de segurança
 * 
 * Fornece métodos estáticos para operações de segurança como
 * hashing de senhas, sanitização de dados, geração de tokens
 * e proteção CSRF.
 * 
 * @package App\Core
 * @author  WBL Produções
 * @version 1.0.0
 * @since   1.0.0
 */
final class Security
{
    /**
     * Custo do algoritmo bcrypt para hashing de senhas
     * 
     * @var int
     */
    private const int BCRYPT_COST = 12;
    
    /**
     * Tamanho do token em bytes (será convertido para 64 caracteres hex)
     * 
     * @var int
     */
    private const int TOKEN_LENGTH = 32;
    
    /**
     * Construtor privado para prevenir instanciação
     */
    private function __construct() {}
    
    /**
     * Gera hash seguro de senha usando bcrypt
     * 
     * @param string $password Senha em texto plano
     * @return string Hash da senha
     * 
     * @example
     * ```php
     * $hash = Security::hashPassword('minha-senha-123');
     * ```
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);
    }
    
    /**
     * Verifica se uma senha corresponde ao hash
     * 
     * @param string $password Senha em texto plano
     * @param string $hash Hash armazenado
     * @return bool True se a senha corresponder ao hash
     * 
     * @example
     * ```php
     * if (Security::verifyPassword($password, $storedHash)) {
     *     // Senha correta
     * }
     * ```
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Sanitiza dados removendo tags HTML e caracteres especiais
     * 
     * Processa recursivamente arrays e strings para prevenir XSS.
     * 
     * @param mixed $data Dados a serem sanitizados (string, array ou outro tipo)
     * @return mixed Dados sanitizados
     * 
     * @example
     * ```php
     * $clean = Security::sanitize($_POST['name']);
     * $cleanArray = Security::sanitize($_POST);
     * ```
     */
    public static function sanitize(mixed $data): mixed
    {
        if (is_array($data)) {
            return array_map(fn($value) => self::sanitize($value), $data);
        }
        
        if (is_string($data)) {
            return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
        }
        
        return $data;
    }
    
    /**
     * Gera token criptograficamente seguro
     * 
     * @return string Token hexadecimal de 64 caracteres
     * 
     * @example
     * ```php
     * $token = Security::generateToken();
     * ```
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(self::TOKEN_LENGTH));
    }
    
    /**
     * Valida token CSRF contra o token armazenado na sessão
     * 
     * Usa comparação segura contra timing attacks.
     * 
     * @param string|null $token Token a ser validado
     * @return bool True se o token for válido
     * 
     * @example
     * ```php
     * if (Security::validateCSRF($_POST['csrf_token'])) {
     *     // Token válido, processar formulário
     * }
     * ```
     */
    public static function validateCSRF(?string $token): bool
    {
        return isset($_SESSION['csrf_token']) 
            && $token !== null 
            && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Cria ou retorna token CSRF da sessão
     * 
     * Gera um novo token se não existir na sessão.
     * 
     * @return string Token CSRF
     * 
     * @example
     * ```php
     * <input type="hidden" name="csrf_token" value="<?= Security::createCSRFToken() ?>">
     * ```
     */
    public static function createCSRFToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = self::generateToken();
        }
        return $_SESSION['csrf_token'];
    }
}
