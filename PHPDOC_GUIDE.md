# Guia de Documentação PHPDoc

Este documento explica os padrões de documentação PHPDoc utilizados neste projeto.

## O que é PHPDoc?

PHPDoc é um padrão de documentação para código PHP que usa comentários especiais para descrever classes, métodos, propriedades e funções. É similar ao JavaDoc e permite que IDEs forneçam autocompletar e informações contextuais.

## Estrutura Básica

```php
/**
 * Descrição breve em uma linha
 * 
 * Descrição detalhada opcional que pode
 * ocupar múltiplas linhas.
 * 
 * @tag Valor da tag
 */
```

## Tags Principais

### @package
Define o namespace/pacote da classe:

```php
/**
 * @package App\Core
 */
class Database { }
```

### @author
Identifica o autor do código:

```php
/**
 * @author WBL Produções
 */
```

### @version
Versão atual do componente:

```php
/**
 * @version 1.0.0
 */
```

### @since
Versão em que o componente foi introduzido:

```php
/**
 * @since 1.0.0
 */
```

### @param
Documenta parâmetros de métodos:

```php
/**
 * @param string $email Email do usuário
 * @param int $age Idade (opcional)
 */
public function create(string $email, int $age = 18): void
```

### @return
Documenta o retorno de métodos:

```php
/**
 * @return array|false Array de dados ou false se não encontrado
 */
public function findById(int $id): array|false
```

### @throws
Documenta exceções que podem ser lançadas:

```php
/**
 * @throws RuntimeException Se a conexão falhar
 * @throws InvalidArgumentException Se o ID for inválido
 */
public function connect(): void
```

### @var
Documenta propriedades de classe:

```php
/**
 * Conexão PDO com o banco de dados
 * 
 * @var PDO
 */
private readonly PDO $connection;
```

### @example
Fornece exemplos de uso:

```php
/**
 * @example
 * ```php
 * $user = $userModel->findById(1);
 * echo $user['name'];
 * ```
 */
```

### @deprecated
Marca código obsoleto:

```php
/**
 * @deprecated Desde versão 2.0, use newMethod() ao invés
 */
public function oldMethod(): void
```

### @see
Referencia código relacionado:

```php
/**
 * @see User::authenticate() Para autenticação
 */
```

### @link
Adiciona links externos:

```php
/**
 * @link https://www.php.net/manual/en/book.pdo.php
 */
```

## Exemplos Completos

### Documentando uma Classe

```php
<?php

declare(strict_types=1);

/**
 * Gerenciador de usuários
 * 
 * Fornece métodos para criar, atualizar, deletar e buscar
 * usuários no sistema. Inclui autenticação e validação.
 * 
 * @package App\Models
 * @author  WBL Produções
 * @version 1.0.0
 * @since   1.0.0
 * @link    https://github.com/wblproducoes/mvc07
 * 
 * @example
 * ```php
 * $userModel = new User();
 * $users = $userModel->findAll();
 * ```
 */
final class User extends Model
{
    /**
     * Nome da tabela no banco de dados
     * 
     * @var string
     */
    protected string $table = 'users';
}
```

### Documentando um Método

```php
/**
 * Autentica usuário com email e senha
 * 
 * Verifica se o email existe no banco de dados e se a senha
 * fornecida corresponde ao hash armazenado. Remove a senha
 * do array retornado por questões de segurança.
 * 
 * @param string $email Email do usuário
 * @param string $password Senha em texto plano
 * @return array|false Dados do usuário (sem senha) ou false se falhar
 * @throws PDOException Se houver erro no banco de dados
 * 
 * @example
 * ```php
 * $user = $userModel->authenticate('user@example.com', 'senha123');
 * if ($user) {
 *     $_SESSION['user_id'] = $user['id'];
 *     echo "Bem-vindo, " . $user['name'];
 * } else {
 *     echo "Credenciais inválidas";
 * }
 * ```
 */
public function authenticate(string $email, string $password): array|false
{
    $user = $this->findByEmail($email);
    
    if ($user && Security::verifyPassword($password, $user['password'])) {
        unset($user['password']);
        return $user;
    }
    
    return false;
}
```

### Documentando Propriedades

```php
/**
 * Instância única da classe (Singleton)
 * 
 * Armazena a única instância permitida da classe Database
 * para garantir uma única conexão com o banco de dados.
 * 
 * @var self|null
 */
private static ?self $instance = null;

/**
 * Conexão PDO com o banco de dados
 * 
 * Propriedade readonly que armazena a conexão ativa
 * com o banco de dados MySQL.
 * 
 * @var PDO
 */
private readonly PDO $connection;
```

### Documentando Constantes

```php
/**
 * Custo do algoritmo bcrypt para hashing de senhas
 * 
 * Valor mais alto = mais seguro mas mais lento.
 * Recomendado: 10-12 para produção.
 * 
 * @var int
 */
private const int BCRYPT_COST = 12;
```

## Tipos de Dados

### Tipos Simples
- `string` - String
- `int` - Inteiro
- `float` - Número decimal
- `bool` - Booleano
- `array` - Array
- `object` - Objeto
- `resource` - Recurso
- `null` - Nulo
- `mixed` - Qualquer tipo
- `void` - Sem retorno

### Union Types (PHP 8+)
```php
/**
 * @param string|int $id ID como string ou inteiro
 * @return array|false Array de dados ou false
 */
```

### Array Types
```php
/**
 * @param array<string> $names Array de strings
 * @param array<int, User> $users Array de objetos User indexado por int
 * @return array{id: int, name: string} Array associativo com estrutura específica
 */
```

### Nullable Types
```php
/**
 * @param string|null $email Email opcional
 * @return User|null Usuário ou null se não encontrado
 */
```

## Boas Práticas

### 1. Seja Conciso mas Completo
```php
// ❌ Ruim - muito vago
/**
 * Faz algo
 */

// ✅ Bom - claro e específico
/**
 * Autentica usuário verificando email e senha
 */
```

### 2. Use Exemplos
```php
/**
 * Sanitiza dados removendo HTML e caracteres especiais
 * 
 * @example
 * ```php
 * $clean = Security::sanitize($_POST['name']);
 * ```
 */
```

### 3. Documente Exceções
```php
/**
 * @throws RuntimeException Se o arquivo não existir
 * @throws InvalidArgumentException Se o caminho for inválido
 */
```

### 4. Mantenha Atualizado
```php
// ❌ Ruim - documentação desatualizada
/**
 * @param string $name
 */
public function create(string $name, string $email): void

// ✅ Bom - documentação correta
/**
 * @param string $name Nome do usuário
 * @param string $email Email do usuário
 */
public function create(string $name, string $email): void
```

### 5. Use @deprecated para Código Obsoleto
```php
/**
 * @deprecated Desde v2.0, use sendEmail() ao invés
 * @see Mailer::sendEmail()
 */
public function mail(): void
```

## Ferramentas

### PHPDoc Generator
Gera documentação HTML a partir dos comentários:

```bash
composer require --dev phpdocumentor/phpdocumentor
vendor/bin/phpdoc -d app -t docs
```

### IDE Support
IDEs modernos usam PHPDoc para:
- Autocompletar
- Verificação de tipos
- Navegação de código
- Refatoração
- Hints de parâmetros

### PHPStan/Psalm
Analisadores estáticos que usam PHPDoc:

```bash
composer require --dev phpstan/phpstan
vendor/bin/phpstan analyse app
```

## Checklist de Documentação

- [ ] Todas as classes têm descrição e @package
- [ ] Todos os métodos públicos estão documentados
- [ ] Todos os parâmetros têm @param
- [ ] Todos os retornos têm @return
- [ ] Exceções estão documentadas com @throws
- [ ] Propriedades têm @var com tipo
- [ ] Exemplos fornecidos para métodos complexos
- [ ] Código obsoleto marcado com @deprecated
- [ ] Tipos estão corretos e atualizados

## Recursos Adicionais

- [PHPDoc Official](https://docs.phpdoc.org/)
- [PSR-5 (Draft)](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md)
- [PHPStan Documentation](https://phpstan.org/writing-php-code/phpdocs-basics)
