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
   CONTENU PRINCIPAL
========================= */

// CAS 1 : clic sur une catégorie → afficher les TYPES
if (!$typeId) {
    $stmt = $pdo->prepare("
        SELECT 
            t.ID,
            t.LIBELLE,
            NULL as VIGNETTE
        FROM types t
        INNER JOIN categorie_type ct ON ct.TYPE = t.ID
        WHERE ct.CATEGORIE = ?
        ORDER BY t.LIBELLE ASC
    ");
    $stmt->execute([$categorieId]);
    $typesAffiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// CAS 2 : clic sur catégorie + type → afficher les THÉMATIQUES
if ($typeId) {
    $stmt = $pdo->prepare("
        SELECT DISTINCT
            th.ID,
            th.LIBELLE,
            th.VIGNETTE
        FROM themes th
        INNER JOIN categorie_type_thematique ctt 
            ON ctt.THEME = th.ID
        INNER JOIN categorie_type ct
            ON ct.ID = ctt.TYPE
        WHERE ct.CATEGORIE = ?
        AND ct.TYPE = ?
        ORDER BY th.LIBELLE ASC
    ");
    $stmt->execute([$categorieId, $typeId]);
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


<body class="page-theme">

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
                        <a href="theme.php?categorie_id=<?= $categorieId ?>&type_id=<?= $type['ID'] ?>">
                            <img src="<?= htmlspecialchars($type['VIGNETTE']) ?>" alt="<?= htmlspecialchars($type['LIBELLE']) ?>">

                            <h3><?= htmlspecialchars($type['LIBELLE']) ?></h3>
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
                        <a href="produits.php?categorie_id=<?= $categorieId ?>&type_id=<?= $typeId ?>&thematique_id=<?= $theme['ID'] ?>">                          
                            <img src="<?= htmlspecialchars($theme['VIGNETTE']) ?>" alt="<?= htmlspecialchars($theme['LIBELLE']) ?>">
                            <div class="theme-overlay">
                                <span class="theme-btn">
                                    Choisir cette thématique <i class="fas fa-angle-right"></i>
                                </span>
                            </div>
                            <h3><?= htmlspecialchars($theme['LIBELLE']) ?></h3>
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

