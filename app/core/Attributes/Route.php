<?php

declare(strict_types=1);

/**
 * Atributo para definir rotas - PHP 8.4+
 */
#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET',
        public bool $requireAuth = true
    ) {}
}
