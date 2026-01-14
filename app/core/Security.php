<?php

declare(strict_types=1);

/**
 * Classe de segurança - PHP 8.4+
 */
final class Security
{
    private const int BCRYPT_COST = 12;
    private const int TOKEN_LENGTH = 32;
    
    private function __construct() {}
    
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => self::BCRYPT_COST]);
    }
    
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
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
    
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(self::TOKEN_LENGTH));
    }
    
    public static function validateCSRF(?string $token): bool
    {
        return isset($_SESSION['csrf_token']) 
            && $token !== null 
            && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function createCSRFToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = self::generateToken();
        }
        return $_SESSION['csrf_token'];
    }
}
