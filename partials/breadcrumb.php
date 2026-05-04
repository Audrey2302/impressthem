<?php
$thematiqueId = $_GET['thematique_id'] ?? null;
// =========================
// BREADCRUMB LOGIQUE
// =========================

$breadcrumb = [];

// 👉 Catégorie
$stmt = $pdo->prepare("
    SELECT LIBELLE, VIGNETTE, CARROUSEL
    FROM categories
    WHERE ID = ?
    LIMIT 1
");
$stmt->execute([$categorieId]);
$categorieActuelle = $stmt->fetch(PDO::FETCH_ASSOC);

if ($categorieActuelle) {
    $breadcrumb[] = [
        'label' => $categorieActuelle['LIBELLE'],
        'url' => 'theme.php?categorie_id=' . $categorieId
    ];
}



// 👉 Type (si présent)
// 👉 Type (si présent)
if ($typeId) {
    $stmt = $pdo->prepare("
        SELECT 
            ct.LIBELLE,
            ct.DESCRIPTION,
            t.LIBELLE as nom
        FROM categorie_type ct
        JOIN types t ON t.ID = ct.TYPE
        WHERE ct.CATEGORIE = ?
        AND ct.TYPE = ?
        LIMIT 1
    ");
    $stmt->execute([$categorieId, $typeId]);
    $typeActuel = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($typeActuel) {
        $breadcrumb[] = [
            'label' => $typeActuel['nom']
        ];
    }
}

// 👉 Thématique (si présente)
if ($thematiqueId) {
    $stmt = $pdo->prepare("
        SELECT LIBELLE
        FROM themes
        WHERE ID = ?
        LIMIT 1
    ");
    $stmt->execute([$thematiqueId]);
    $themeActuel = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($themeActuel) {
        $breadcrumb[] = [
            'label' => $themeActuel['nom']
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