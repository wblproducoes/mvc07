<?php

declare(strict_types=1);

/**
 * Enum para roles de usuário - PHP 8.4+
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::USER => 'Usuário',
        };
    }
    
    public function hasPermission(string $permission): bool
    {
        return match($this) {
            self::ADMIN => true,
            self::USER => in_array($permission, ['read', 'update_own']),
        };
    }
}
