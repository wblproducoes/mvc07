<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?= BASE_URL ?>" class="logo"><?= APP_NAME ?></a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <ul class="nav-menu">
                    <li><a href="<?= BASE_URL ?>/home">Dashboard</a></li>
                    <li><a href="<?= BASE_URL ?>/users">Usuários</a></li>
                    <li><a href="<?= BASE_URL ?>/about">Sobre</a></li>
                    <li><a href="<?= BASE_URL ?>/auth/logout">Sair</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>
    <main class="container">
