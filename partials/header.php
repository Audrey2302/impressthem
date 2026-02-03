<?php
/* =========================
   DONNÉES COMMUNES AU HEADER
   (menus catégories + types)
========================= */

// 1️⃣ On récupère TOUTES les catégories depuis la base
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");

// On transforme le résultat SQL en tableau PHP
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Exemple :
// [
//   ['id' => 1, 'titre' => 'Mariage'],
//   ['id' => 2, 'titre' => 'Anniversaire']
// ]


// 2️⃣ On récupère TOUS les types depuis la base
$stmt = $pdo->query("SELECT * FROM types ORDER BY id ASC");

// On transforme le résultat SQL en tableau PHP
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Exemple :
// [
//   ['id' => 1, 'nom' => 'Faire-part', 'categorie_id' => 1],
//   ['id' => 2, 'nom' => 'Carte', 'categorie_id' => 1]
// ]


// 3️⃣ On regroupe les types par catégorie
$typesParCategorie = [];

// On parcourt chaque type
foreach ($types as $type) {

    // On classe chaque type dans la bonne catégorie
    // clé = categorie_id
    $typesParCategorie[$type['categorie_id']][] = $type;
}

/*
Résultat final dans $typesParCategorie :

[
  1 => [ // catégorie ID 1
        ['id' => 1, 'nom' => 'Faire-part', 'categorie_id' => 1],
        ['id' => 2, 'nom' => 'Carte', 'categorie_id' => 1]
       ],
  2 => [ // catégorie ID 2
        ['id' => 3, 'nom' => 'Invitation', 'categorie_id' => 2]
       ]
]

*/
?>


<!--                         -->
<!--         HEADER          -->
<!--                         -->
<header id="header" class="header">
    <div class="container header-content">
        <div class="LogoNav">
            
                <img src="https://impressthem.fr/images/logo.png" alt="LOGO" width="110" height="110">
            
            
            <nav class="nav">

                <ul class="menu">
                    <li><a href="index.php">Accueil</a></li>

                    <?php foreach ($categories as $categorie): ?>
                        <li class="has-sub">
                            <a href="theme.php?categorie_id=<?= $categorie['id'] ?>">
                                <?= htmlspecialchars($categorie['titre']) ?>
                            </a>

                            <?php if (!empty($typesParCategorie[$categorie['id']])): ?>
                                <ul class="submenu">
                                    <?php foreach ($typesParCategorie[$categorie['id']] as $type): ?>
                                        <li>
                                            <a href="theme.php?categorie_id=<?= $categorie['id'] ?>&type_id=<?= $type['id'] ?>">
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
                <a href="contact.php" title="Contact"><i class="far fa-comment-alt"></i></a>
                <a href="connexion.php" title="Login"><i class="far fa-user"></i></a>
                
            </div>
            
            <div class="cart">
                <img src="https://impressthem.fr/img/icons/cart-bag.svg" alt="Panier">
                <span>0,00 € TTC</span>
            </div>
        </div> 

        <button class="burger" data-toggle="collapse" data-target="nav">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
<!-- MENU MOBILE -->
<div class="mobile-nav-overlay"></div>

<nav class="mobile-nav">
    <button class="mobile-close">&times;</button>

    <ul class="mobile-menu">
        <li>
            <a href="index.php">Accueil</a>
        </li>

        <?php foreach ($categories as $categorie): ?>
            <li class="mobile-has-sub">
                <button class="mobile-category-toggle">
                    <?= htmlspecialchars($categorie['titre']) ?>
                    <span class="arrow">▾</span>
                </button>

                <?php if (!empty($typesParCategorie[$categorie['id']])): ?>
                    <ul class="mobile-submenu">
                        <?php foreach ($typesParCategorie[$categorie['id']] as $type): ?>
                            <li>
                                <a href="theme.php?categorie_id=<?= $categorie['id'] ?>&type_id=<?= $type['id'] ?>">
                                    <?= htmlspecialchars($type['nom']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <ul class="mobile-submenu">
                        <li>
                            <a href="theme.php?categorie_id=<?= $categorie['id'] ?>">
                                Voir la catégorie
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
