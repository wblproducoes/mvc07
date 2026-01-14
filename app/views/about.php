<?php require_once 'app/views/layouts/header.php'; ?>

<div class="dashboard">
    <h1>Sobre o Sistema</h1>
    
    <div class="card" style="max-width: 800px; margin: 0 auto; text-align: left;">
        <h2><?= htmlspecialchars($info['name']) ?></h2>
        
        <div style="margin: 2rem 0;">
            <p><strong>Versão:</strong> <?= htmlspecialchars($info['formatted']) ?></p>
            <?php if ($info['releaseDate']): ?>
                <p><strong>Data de Lançamento:</strong> <?= htmlspecialchars($info['releaseDate']) ?></p>
            <?php endif; ?>
            <p><strong>Estabilidade:</strong> <?= htmlspecialchars(ucfirst($info['stability'])) ?></p>
        </div>
        
        <div style="margin: 2rem 0;">
            <h3>Ambiente PHP</h3>
            <p><strong>Versão Atual:</strong> <?= htmlspecialchars($info['php']['current']) ?></p>
            <p><strong>Versão Mínima:</strong> <?= htmlspecialchars($info['php']['minimum']) ?></p>
            <p><strong>Versão Recomendada:</strong> <?= htmlspecialchars($info['php']['recommended']) ?></p>
            
            <?php if (Version::checkPhpCompatibility()): ?>
                <p style="color: #27ae60;">✓ PHP compatível</p>
            <?php else: ?>
                <p style="color: #e74c3c;">✗ PHP incompatível - atualize para versão <?= htmlspecialchars($info['php']['minimum']) ?> ou superior</p>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($info['features'])): ?>
            <div style="margin: 2rem 0;">
                <h3>Recursos</h3>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($info['features'] as $feature): ?>
                        <li style="padding: 0.5rem 0;">✓ <?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div style="margin: 2rem 0;">
            <a href="<?= BASE_URL ?>/CHANGELOG.md" class="btn btn-secondary" target="_blank">Ver Changelog</a>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
