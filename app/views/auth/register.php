<?php require_once 'app/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Registrar</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?= BASE_URL ?>/auth/register">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        
        <p class="text-center">
            Já tem conta? <a href="<?= BASE_URL ?>/auth/login">Faça login</a>
        </p>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
