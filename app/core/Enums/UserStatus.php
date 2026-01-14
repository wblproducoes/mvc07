<?php

declare(strict_types=1);

/**
 * Enum para status de usuário - PHP 8.4+
 */
enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Ativo',
            self::INACTIVE => 'Inativo',
        };
    }
    
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
