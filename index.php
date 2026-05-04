<?php
/* =====================================================
   CONNEXION BDD
===================================================== */
require_once __DIR__ . '/config/db.php';

/* =====================================================
   RÉCUPÉRATION DES CATÉGORIES
===================================================== */
$sql = "
        SELECT 
        ID, 
        LIBELLE, 
        DESCRIPTION, 
        VIGNETTE 
    FROM categories
    ORDER BY ID ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================================================
   RÉCUPÉRATION DES TYPES PAR CATÉGORIE
===================================================== */
$sql = "
        SELECT
            c.ID AS categorie_id,
            t.ID AS type_id,
            t.LIBELLE AS type_nom
        FROM categories c
        INNER JOIN categorie_type ct ON ct.CATEGORIE = c.ID
        INNER JOIN types t ON t.ID = ct.TYPE
        ORDER BY c.ID, t.LIBELLE
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================================================
   ORGANISATION DES TYPES PAR CATÉGORIE
===================================================== */
$typesParCategorie = [];

foreach ($rows as $row) {
    $typesParCategorie[$row['categorie_id']][] = [
        'id'  => $row['type_id'],
        'nom' => $row['type_nom']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impress Them - Faire-part, Invitation, Menu, Remerciement personnalisés</title>
    
    <link rel="stylesheet" href="./styles/main.css">
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500,600,700,900%7COpen+Sans:300,400,600,700,800%7CPermanent+Marker" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- ================= CARROUSEL ================= -->
<div class="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" style="background-image: url('https://impressthem.fr/storage/crsxeBFOY5d87AeEOcMwvvzivvWhZ1K0qsNMaIga.jpg');"></div>
    <div class="carousel-item" style="background-image: url('https://impressthem.fr/storage/qVHjvQzbHmnvb9qzGSy1YuvabBCqMJcEmDv5rnB7.jpg');"></div>
    <div class="carousel-item" style="background-image: url('https://impressthem.fr/storage/Mfnisw9a8zXN3OfTECBUmZNWKSriDYhMLYgWcx2f.jpg');"></div>
  </div>
  <button class="carousel-control prev">&#10094;</button>
  <button class="carousel-control next">&#10095;</button>
  <div class="carousel-bullets"></div>
</div>

<!-- ================= CARDS ================= -->
<section class="cards">
  <div class="container">
    <div class="row">

      <?php foreach ($categories as $categorie): ?>
        <div class="col-md-4 mb-5">
          <div class="card-box">
            <a href="theme.php?categorie_id=<?= $categorie['ID'] ?>">
              <div class="card-img">
                <img src="<?= $categorie['VIGNETTE'] ?>" alt="<?= htmlspecialchars($categorie['LIBELLE']) ?>">
              </div>
              <div class="card-info">
                <h2><?= htmlspecialchars($categorie['LIBELLE']) ?></h2>
                <p><?= htmlspecialchars($categorie['DESCRIPTION']) ?></p>
                <button class="card-btn">Voir les produits</button>
              </div>
            </a>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- ================= NOS VALEURS ================= -->
<section class="valeurs-section">
    <div class="container">
        <h2 class="valeurs-title">Nos valeurs</h2>
        <div class="valeurs-grid">

            <div class="valeur-item">
                <div class="valeur-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <div class="valeur-content">
                    <h4>Une forte innovation</h4>
                    <p>Vous pouvez faire varier vos textes en fonction du convive (civilité, nom, prénom, etc...).</p>
                </div>
            </div>

            <div class="valeur-item">
                <div class="valeur-icon"><i class="far fa-handshake"></i></div>
                <div class="valeur-content">
                    <h4>Un accompagnement personnalisé</h4>
                    <p>Nous sommes à vos côtés dans l’élaboration de votre communication, tant par l’utilisation de nos gabarits que par la conception de vos idées créatives.</p>
                </div>
            </div>

            <div class="valeur-item">
                <div class="valeur-icon"><i class="fa-solid fa-gift"></i></div>
                <div class="valeur-content">
                    <h4>Des prix avantageux</h4>
                    <p>Nous nous efforçons de vous proposer les prix les plus adaptés, en fonction du modèle, de la fabrication et de la quantité choisis.</p>
                </div>
            </div>

            <div class="valeur-item">
                <div class="valeur-icon"><i class="fa-regular fa-circle-check"></i></div>
                <div class="valeur-content">
                    <h4>Un suivi qualité</h4>
                    <p>Nous vous garantissons un suivi qualité jusqu'à l'expédition de votre colis.</p>
                </div>
            </div>

            <div class="valeur-item">
                <div class="valeur-icon"><i class="fa-solid fa-briefcase"></i></div>
                <div class="valeur-content">
                    <h4>Une expérience de la communication</h4>
                    <p>Fort d'une expérience de +10 ans dans le marketing direct, notre savoir-faire est à votre service.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= TEMOIGNAGES ================= -->
<section class="testimonials">
    <div class="container">
        <h2 class="testi-title">Témoignages clients</h2>
        <p class="testi-subtitle">Ils nous ont fait confiance... pourquoi pas vous ?</p>

        <div class="testimonials-widget">
            <div id="wp-widget-reviews">
                <div id="wp-widget-preview">
                    Lire
                    <a href="https://www.mariages.net/faire-part-mariage/impress-them--e244844/avis" rel="nofollow">nos avis</a>
                    à&nbsp;
                    <a href="https://www.mariages.net" rel="nofollow">
                        <img src="https://cdn1.mariages.net/assets/img/logos/gen_logoHeader.svg" height="20" alt="Mariages.net">
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<script src="https://cdn1.mariages.net/js/wp-widget.js?symfnw-FR48-1-20260122-011-1_www_m_"></script>
<script>wpShowReviews(244844, "white");</script>

<!-- ================= FOOTER ================= -->
<?php require_once __DIR__ . '/partials/footer.php'; ?>

</body>
<script src="./scripts/header-script.js"></script>
<script src="./scripts/carousel-script.js"></script>
</html>
