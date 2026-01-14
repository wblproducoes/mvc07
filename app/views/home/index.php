<?php require_once 'app/views/layouts/header.php'; ?>

<div class="dashboard">
    <h1>Bem-vindo, <?= htmlspecialchars($user) ?>!</h1>
    
    <div class="dashboard-grid">
        <div class="card">
            <h3>Usuários</h3>
            <p class="card-number">0</p>
            <a href="<?= BASE_URL ?>/users" class="btn btn-secondary">Ver todos</a>
        </div>
        
        <div class="card">
            <h3>Relatórios</h3>
            <p class="card-number">0</p>
            <a href="<?= BASE_URL ?>/reports" class="btn btn-secondary">Ver todos</a>
        </div>
        
        <div class="card">
            <h3>Configurações</h3>
            <p class="card-number">-</p>
            <a href="<?= BASE_URL ?>/settings" class="btn btn-secondary">Acessar</a>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
