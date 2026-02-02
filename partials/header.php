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
                <a href=""><i class="far fa-user"></i></a>
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
