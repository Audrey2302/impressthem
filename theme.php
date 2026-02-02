<?php
require_once __DIR__ . '/config/db.php';

/* =========================
   PARAMÈTRES URL
========================= */
$categorieId = $_GET['categorie_id'] ?? null;
$typeId = $_GET['type_id'] ?? null;

if (!$categorieId) {
    die('Catégorie manquante 😅');
}

/* =========================
   HEADER : catégories + types
========================= */
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM types ORDER BY id ASC");
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

$typesParCategorie = [];
foreach ($types as $type) {
    $typesParCategorie[$type['categorie_id']][] = $type;
}

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



/* =========================
   CONTENU PRINCIPAL
========================= */

// CAS 1 : clic sur une catégorie → afficher les TYPES
if (!$typeId) {
    $stmt = $pdo->prepare("
        SELECT id, nom, image_url
        FROM types
        WHERE categorie_id = ?
        ORDER BY id ASC
    ");
    $stmt->execute([$categorieId]);
    $typesAffiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// CAS 2 : clic sur catégorie + type → afficher les THÉMATIQUES
if ($typeId) {
    $stmt = $pdo->prepare("
        SELECT nom, image_url
        FROM thematiques
        WHERE type_id = ?
        ORDER BY nom ASC
    ");
    $stmt->execute([$categorieId]);
    $thematiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Impress Them</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    
<!-- ================= HEADER ================= -->
<?php require_once __DIR__ . '/partials/header.php'; ?>
<!-- ================= breadcrumb ================= -->
<?php require_once __DIR__ . '/partials/breadcrumb.php'; ?>
<!-- ================= descriptif ================= -->
<?php if (!$typeId): ?>
    <?php require_once __DIR__ . '/partials/carrousel.php'; ?>
<?php endif; ?>
<!-- ================= descriptif ================= -->
<?php require_once __DIR__ . '/partials/descriptif.php'; ?>
<!-- ================= CONTENU ================= -->

<!-- 🔹 CAS 1 : TYPES -->
<?php if (!$typeId): ?>
<section class="themes">
    <div class="container">
        <div class="row">

            <?php foreach ($typesAffiches as $type): ?>
                <div class="col-md-4 mb-4">
                    <div class="theme-card">
                        <a href="theme.php?categorie_id=<?= $categorieId ?>&type_id=<?= $type['id'] ?>">
                            <img src="<?= htmlspecialchars($type['image_url']) ?>" alt="<?= htmlspecialchars($type['nom']) ?>">

                            <h3><?= htmlspecialchars($type['nom']) ?></h3>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- 🔹 CAS 2 : THÉMATIQUES -->
<?php if ($typeId): ?>
<section class="themes">
    <div class="container">
        <div class="row">
            <?php foreach ($thematiques as $theme): ?>               
                <div class="col-md-4 mb-4">   
                    <div class="theme-card">
                        <a href="#">
                            <img src="<?= htmlspecialchars($theme['image_url']) ?>" alt="<?= htmlspecialchars($theme['nom']) ?>">
                            <div class="theme-overlay">
                                <span class="theme-btn">
                                    Choisir cette thématique <i class="fas fa-angle-right"></i>
                                </span>
                            </div>
                            <h3><?= htmlspecialchars($theme['nom']) ?></h3>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<?php endif; ?>
<!-- ================= FOOTER ================= -->
<?php require_once __DIR__ . '/partials/footer.php'; ?>

</body>
</html>

