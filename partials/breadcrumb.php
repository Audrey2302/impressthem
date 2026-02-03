<?php
// =========================
// BREADCRUMB LOGIQUE
// =========================

$breadcrumb = [];

// 👉 Catégorie
$stmt = $pdo->prepare("
    SELECT titre, image_url, carrousel
    FROM categories
    WHERE id = ?
    LIMIT 1
");
$stmt->execute([$categorieId]);
$categorieActuelle = $stmt->fetch(PDO::FETCH_ASSOC);

if ($categorieActuelle) {
    $breadcrumb[] = [
        'label' => $categorieActuelle['titre'],
        'url' => 'theme.php?categorie_id=' . $categorieId
    ];
}



// 👉 Type (si présent)
$typeActuel = null;

// 👉 Type (si présent)
if ($typeId) {
    $stmt = $pdo->prepare("
        SELECT nom, titre, texte
        FROM types
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->execute([$typeId]);
    $typeActuel = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($typeActuel) {
        $breadcrumb[] = [
            'label' => $typeActuel['nom']
        ];
    }
}

?>



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