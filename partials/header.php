<?php
// ==============================
// SESSION
// ==============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<?php
$firstname = '';

if ($isLoggedIn) {
    // On récupère le prénom depuis la table users
    $stmt = $pdo->prepare("SELECT firstname FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $headerUser = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($headerUser) {
        $firstname = htmlspecialchars($headerUser['firstname']); // sécurité XSS
    }
}
?>





<?php
/* =====================================================
   HEADER - DONNÉES POUR LE MENU
   Logique :
   Catégorie → Types disponibles (via categorie_type)
===================================================== */

/*
1️⃣ On récupère toutes les catégories
👉 ex : Mariage, Naissance, Anniversaire
*/

$stmt = $pdo->query("
    SELECT ID, LIBELLE
    FROM categories
    ORDER BY ID ASC
");
$headercategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
2️⃣ On récupère les types DISPONIBLES par catégorie
👉 on passe par categorie_type (table intermédiaire)
👉 évite les doublons et respecte la vraie logique
*/
$stmt = $pdo->query("
    SELECT
        c.ID AS categorie_id,
        c.LIBELLE AS categorie_titre,
        t.ID AS type_id,
        t.LIBELLE AS type_nom
    FROM categories c
    JOIN categorie_type ct ON ct.CATEGORIE = c.ID
    JOIN types t ON t.ID = ct.TYPE
    ORDER BY c.ID, t.LIBELLE
");

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
3️⃣ On range les données proprement pour le menu
👉 tableau final :
[
  categorie_id => [
      'titre' => 'Mariage',
      'types' => [
          ['id' => 1, 'nom' => 'Faire-part'],
          ['id' => 2, 'nom' => 'Menu']
      ]
  ]
]
*/
$menu = [];

foreach ($rows as $row) {
    $menu[$row['categorie_id']]['titre'] = $row['categorie_titre'];
    $menu[$row['categorie_id']]['types'][] = [
        'id' => $row['type_id'],
        'nom' => $row['type_nom']
    ];
}
?>


<!--                         -->
<!--         HEADER          -->
<!--                         -->
<header id="header" class="header">
    
<div class="Bonjour">            <!-- Icônes -->
         <?php if ($isLoggedIn && $firstname): ?>
            <div class="welcome-message">
                Bonjour <span class="user-name"><?= $firstname ?></span> !
            </div>
        <?php endif; ?></div>

    <div class="container header-content">



        
        <div class="LogoNav">

            <img src="https://impressthem.fr/images/logo.png" alt="LOGO" width="110" height="110">

            <nav class="nav">
                <ul class="menu">
                    <li><a href="index.php">Accueil</a></li>

                   <?php foreach ($menu as $menuCategorieId => $headercategories): ?>

                        <li class="has-sub">
                            <!-- Lien catégorie -->
                            <a href="theme.php?categorie_id=<?= $menuCategorieId ?>">
                                <?= htmlspecialchars($headercategories['titre']) ?>
                            </a>

                            <!-- Sous-menu des types -->
                            <?php if (!empty($headercategories['types'])): ?>
                                <ul class="submenu">
                                    <?php foreach ($headercategories['types'] as $type): ?>
                                        <li>
                                            <a href="theme.php?categorie_id=<?= $menuCategorieId ?>&type_id=<?= $type['id'] ?>">
                                                <?= htmlspecialchars($type['nom']) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>



        <div class="LogoCart">
            <div class="logo">
                <a href="contact.php"><i class="far fa-comment-alt"></i></a>
                    <?php if ($isLoggedIn): ?>

                    <!-- Utilisateur connecté -->
                    <a href="mon-compte.php" class="user-connected">
                        <i class="fas fa-user"></i>
                        <span class="status-dot"></span>
                    </a>

                    <a href="partials/deconnexion.php" class="logout">
                        <i class="fas fa-right-from-bracket"></i>
                    </a>

                <?php else: ?>

                    <!-- Utilisateur NON connecté -->
                    <a href="connexion.php">
                        <i class="far fa-user"></i>
                    </a>

                <?php endif; ?>




            </div>

            <div class="cart">
                <img src="https://impressthem.fr/img/icons/cart-bag.svg" alt="Panier">
                <span>0,00 € TTC</span>
            </div>
        </div>

        <button class="burger">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<!-- MENU MOBILE -->
<div class="mobile-nav-overlay"></div>

<nav class="mobile-nav">
    <button class="mobile-close">&times;</button>

    <ul class="mobile-menu">
        <li><a href="index.php">Accueil</a></li>

        <?php foreach ($menu as $menuCategorieId => $headercategories): ?>

            <li class="mobile-has-sub">
                <button class="mobile-category-toggle">
                    <?= htmlspecialchars($headercategories['titre']) ?>
                    <span class="arrow">▾</span>
                </button>

                <ul class="mobile-submenu">
                    <?php foreach ($headercategories['types'] as $type): ?>
                        <li>
                            <a href="theme.php?categorie_id=<?= $menuCategorieId ?>&type_id=<?= $type['id'] ?>">
                                <?= htmlspecialchars($type['nom']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
        <?php if ($isLoggedIn): ?>
            <li><a href="mon-compte.php">Mon compte</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="connexion.php">Connexion</a></li>
        <?php endif; ?>

    </ul>
</nav>

