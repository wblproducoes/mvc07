<?php

declare(strict_types=1);

/**
 * Classe para gerenciamento de versão - PHP 8.4+
 */
final class Version
{
    private const string VERSION_FILE = 'VERSION';
    private const string VERSION_JSON_FILE = 'version.json';
    
    private function __construct() {}
    
    public static function get(): string
    {
        if (file_exists(self::VERSION_FILE)) {
            return trim(file_get_contents(self::VERSION_FILE));
        }
        return APP_VERSION;
    }
    
    public static function getInfo(): array
    {
        if (file_exists(self::VERSION_JSON_FILE)) {
            $json = file_get_contents(self::VERSION_JSON_FILE);
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        }
        
        return [
            'version' => self::get(),
            'name' => APP_NAME,
            'releaseDate' => 'N/A'
        ];
    }
    
    public static function compare(string $version1, string $version2): int
    {
        return version_compare($version1, $version2);
    }
    
    public static function isAtLeast(string $version): bool
    {
        return self::compare(self::get(), $version) >= 0;
    }
    
    public static function getFormatted(): string
    {
        $info = self::getInfo();
        $version = self::get();
        $codename = isset($info['codename']) ? " \"{$info['codename']}\"" : '';
        
        return "v{$version}{$codename}";
    }
    
    public static function getFullInfo(): array
    {
        $info = self::getInfo();
        
        return [
            'version' => self::get(),
            'formatted' => self::getFormatted(),
            'name' => $info['name'] ?? APP_NAME,
            'codename' => $info['codename'] ?? null,
            'releaseDate' => $info['releaseDate'] ?? null,
            'stability' => $info['stability'] ?? 'stable',
            'php' => [
                'current' => PHP_VERSION,
                'minimum' => $info['php']['minimum'] ?? '8.4.0',
                'recommended' => $info['php']['recommended'] ?? '8.5.0'
            ],
            'features' => $info['features'] ?? []
        ];
    }
    
    public static function checkPhpCompatibility(): bool
    {
        $info = self::getInfo();
        $minVersion = $info['php']['minimum'] ?? '8.4.0';
        
        return version_compare(PHP_VERSION, $minVersion, '>=');
    }
}
