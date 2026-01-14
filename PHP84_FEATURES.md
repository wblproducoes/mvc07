# Recursos PHP 8.4+ Implementados

Este documento descreve os recursos modernos do PHP 8.4+ utilizados neste sistema.

## 1. Strict Types (Tipagem Forte)

Todos os arquivos utilizam `declare(strict_types=1)` para garantir tipagem rigorosa.

```php
declare(strict_types=1);
```

## 2. Propriedades Readonly

Propriedades imutáveis após inicialização:

```php
final class Database
{
    private readonly PDO $connection;
}
```

## 3. Enums

Tipos enumerados para valores fixos:

```php
enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
```

## 4. Union Types

Múltiplos tipos de retorno:

```php
public function findById(int $id): array|false
{
    // Retorna array ou false
}
```

## 5. Mixed Type

Tipo genérico para dados variados:

```php
public static function sanitize(mixed $data): mixed
{
    // Aceita qualquer tipo
}
```

## 6. Arrow Functions

Funções anônimas concisas:

```php
$fields = implode(', ', array_map(
    fn($key) => "{$key} = :{$key}",
    array_keys($data)
));
```

## 7. Atributos (Attributes)

Metadados para classes e métodos:

```php
#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET',
        public bool $requireAuth = true
    ) {}
}
```

## 8. Constructor Property Promotion

Declaração simplificada de propriedades:

```php
public function __construct(
    public string $path,
    public string $method = 'GET'
) {}
```

## 9. Named Arguments

Argumentos nomeados para clareza:

```php
password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
```

## 10. Match Expression

Substituição moderna do switch:

```php
return match($this) {
    self::ADMIN => 'Administrador',
    self::USER => 'Usuário',
};
```

## 11. Nullsafe Operator

Acesso seguro a propriedades:

```php
$name = $user?->name ?? 'Anônimo';
```

## 12. Never Type

Funções que nunca retornam:

```php
protected function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
```

## 13. Classes Final

Prevenir herança indevida:

```php
final class Security { }
final class Database { }
```

## 14. Abstract Classes

Classes base que não podem ser instanciadas:

```php
abstract class Model { }
abstract class Controller { }
```

## 15. Tratamento de Erros Moderno

Uso de Throwable e tratamento robusto:

```php
try {
    call_user_func_array([$this->controller, $this->method], $this->params);
} catch (Throwable $e) {
    $this->handleError($e);
}
```

## 16. JSON com Flags Modernas

```php
json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
json_decode($json, true, 512, JSON_THROW_ON_ERROR);
```

## 17. Constantes de Classe Tipadas

```php
private const int BCRYPT_COST = 12;
private const int TOKEN_LENGTH = 32;
private const string VERSION_FILE = 'VERSION';
```

## Benefícios

- **Segurança de Tipos**: Erros detectados em tempo de desenvolvimento
- **Performance**: Otimizações do PHP 8.4+
- **Manutenibilidade**: Código mais claro e autodocumentado
- **Modernidade**: Uso de recursos state-of-the-art
- **Prevenção de Bugs**: Tipagem forte previne erros comuns
