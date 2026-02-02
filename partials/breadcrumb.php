<nav class="breadcrumb">
    <div class="container">
        <a href="index.php">Accueil</a>

        <?php foreach ($breadcrumb as $item): ?>
            <span>›</span>

            <?php if (!empty($item['url'])): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>">
                    <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php else: ?>
                <span><?= htmlspecialchars($item['label']) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</nav>