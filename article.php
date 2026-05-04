<?php
require_once __DIR__ . '/config/db.php';

/* =========================
   PARAMÈTRE URL
========================= */
$reference = $_GET['reference'] ?? null;

if (!$reference) {
    die("Produit introuvable 😅");
}

/* =========================
   PRODUIT
========================= */
$stmt = $pdo->prepare("
    SELECT p.*, f.nom as format_nom
    FROM produits p
    LEFT JOIN formats f ON f.id = p.format_ouvert_id
    WHERE p.reference = ?
");
$stmt->execute([$reference]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit non trouvé 😭");
}


/* =========================
   SUPPORTS
========================= */
$stmt = $pdo->query("
    SELECT id, nom 
    FROM supports
    WHERE actif = 1
");
$supports = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   TARIFS (par défaut support 1)
========================= */
$defaultSupport = $supports[0]['id'];

$stmt = $pdo->prepare("
    SELECT quantite_min, prix_unitaire
    FROM tarifs_quantite
    WHERE format_id = ?
    AND support_id = ?
    ORDER BY quantite_min ASC
");

$stmt->execute([$produit['format_ouvert_id'], $defaultSupport]);
$tarifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categorieId  = $produit['categorie_id'];
$typeId       = $produit['type_id'];
$thematiqueId = $produit['thematique_id'];

?>
<!---------------------------------------------------Début HTML--------------------------------------------------------->
<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="page-produit">

<!-- ================= HEADER ================= -->
<?php require_once __DIR__ . '/partials/header.php'; ?>


<!-- ================= breadcrumb ================= -->
<?php require_once __DIR__ . '/partials/breadcrumb.php'; ?>


<section class="product-detail">
    <div class="container">
        <div class="row">

            <!-- IMAGE -->
            <div class="col-md-5">
                <div class="product-gallery">
                    <img src="./uploads/<?= htmlspecialchars($produit['thumbnail']) ?>" alt="">
                </div>
            </div>

            <!-- INFOS -->
            <div class="col-md-7">
                <div class="product-info">

                    <h1><?= htmlspecialchars($produit['modele']) ?></h1>

                    <p class="product-format">
                        Format : <?= htmlspecialchars($produit['format_nom']) ?>
                    </p>

                    <hr>

                    <!-- SUPPORT -->
                    <div class="product-option">
                        <label>Support :</label>

                        <select id="support">
                            <?php foreach ($supports as $support): ?>
                                <option value="<?= $support['id'] ?>">
                                    <?= htmlspecialchars($support['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <hr>

                    <!-- QUANTITÉ -->
                    <div class="product-option">
                        <label>Quantité :</label>

                        <div class="quantity-box">
                            <button class="minus">-</button>
                            <input type="number" id="quantity" value="1" min="1">
                            <button class="plus">+</button>
                        </div>

                        <span id="price">
                            <?= $tarifs[0]['prix_unitaire'] ?> € TTC
                        </span>
                    </div>

                    <a href="#perso" class="btn-primary">
                        Je personnalise
                    </a>

                    <hr>

                    <!-- TABLE TARIFS -->
                    <table class="price-table">
                        <tr>
                            <th>Quantité</th>
                            <th>Prix</th>
                        </tr>

                        <?php foreach ($tarifs as $t): ?>
                            <tr>
                                <td><?= $t['quantite_min'] ?> et +</td>
                                <td><?= $t['prix_unitaire'] ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<?php require_once __DIR__ . '/partials/footer.php'; ?>


</body>
</html>