<?php
/* =====================================================
   CONNEXION BDD
===================================================== */
require_once __DIR__ . '/config/db.php';

/* =====================================================
   PARAMÈTRES URL
===================================================== */

$categorieId   = isset($_GET['categorie_id']) ? (int)$_GET['categorie_id'] : 0;
$typeId        = isset($_GET['type_id']) ? (int)$_GET['type_id'] : 0;
$thematiqueId  = isset($_GET['thematique_id']) ? (int)$_GET['thematique_id'] : null;

if (!$categorieId || !$typeId) {
    die("Paramètres manquants.");
}

/* =====================================================
   INFOS CATÉGORIE / TYPE / THÉMATIQUE
===================================================== */

$stmt = $pdo->prepare("
SELECT LIBELLE FROM categories
WHERE id = ?
");
$stmt->execute([$categorieId]);
$categorie = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
SELECT LIBELLE FROM types
WHERE id = ?
");
$stmt->execute([$typeId]);
$type = $stmt->fetch(PDO::FETCH_ASSOC);

$thematique = null;

if ($thematiqueId) {
    $stmt = $pdo->prepare("
     SELECT LIBELLE FROM themes
    WHERE id = ?
    ");
    $stmt->execute([$thematiqueId]);
    $thematique = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* =====================================================
   REQUÊTE PRODUITS
===================================================== */

$sql = "
SELECT *
FROM produits
WHERE CATEGORIE = ?
AND TYPE = ?
";

$params = [$categorieId, $typeId];

if ($thematiqueId) {
    $sql .= " AND THEME = ?";
    $params[] = $thematiqueId;
}

$sql .= " ORDER BY modele ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="page-produits">

<!-- ================= HEADER ================= -->
<?php require_once __DIR__ . '/partials/header.php'; ?>


<!-- ================= breadcrumb ================= -->
<?php require_once __DIR__ . '/partials/breadcrumb.php'; ?>



<!-- ================= PRODUITS ================= -->

<section class="produits">
    <div class="container">
        <div class="products-grid">

            <?php foreach ($produits as $produit): ?>
                <div class="product-item">
                    <div class="product-card">

                        <a href="article.php?reference=<?= urlencode($produit['reference']) ?>">

                            <div class="product-image">
                                <img src="asset/produits/mariage/faire-part/FAI-MAR-CIN/<?= htmlspecialchars($produit['VIGNETTE']) ?>" 
                                     alt="<?= htmlspecialchars($produit['MODELE']) ?>">
                            </div>

                            <div class="product-info">
                                <h3><?= htmlspecialchars($produit['MODELE']) ?></h3>
                            </div>

                        </a>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>



<!-- ================= FOOTER ================= -->
<?php require_once __DIR__ . '/partials/footer.php'; ?>


<script src="./scripts/header-script.js"></script>

</body>
</html>
