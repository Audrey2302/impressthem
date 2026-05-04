<?php
require_once __DIR__ . '/config/db.php';
session_start();

$tab = $_GET['tab'] ?? 'infos';

// 🔒 Sécurité : utilisateur non connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupération des infos utilisateur
$stmt = $pdo->prepare("
    SELECT firstname, lastname, email, phone, address, address_complement,
           postal_code, city, country, created_at
    FROM users
    WHERE id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php require_once __DIR__ . '/partials/header.php'; ?>

<main class="account">
    <div class="account__container">

        <h1 class="account__title">
            Bonjour <?= htmlspecialchars($user['firstname']) ?> 👋
        </h1>

        <p class="account__subtitle">
            Bienvenue dans votre espace personnel
            
        </p>
        <div class="account__tabs">

            <a href="mon-compte.php?tab=infos"
            class="account__tab <?= $tab === 'infos' ? 'active' : '' ?>">
                Mes informations
            </a>

            <a href="mon-compte.php?tab=invites"
            class="account__tab <?= $tab === 'invites' ? 'active' : '' ?>">
                Carnet d’adresse 💌
            </a>

        </div>


        <?php if ($tab === 'infos'): ?>

        <div class="account__card">
            <h2>Mes informations</h2>

            <ul class="account__list">
                <li><strong>Nom :</strong> <?= htmlspecialchars($user['lastname']) ?></li>
                <li><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></li>
                <li><strong>Téléphone :</strong> <?= htmlspecialchars($user['phone'] ?? '—') ?></li>
                <li>
                    <strong>Adresse :</strong>
                    <?= htmlspecialchars($user['address']) ?>
                    <?= $user['address_complement'] ? ' - ' . htmlspecialchars($user['address_complement']) : '' ?>
                </li>
                <li><strong>Ville :</strong> <?= htmlspecialchars($user['postal_code']) ?> <?= htmlspecialchars($user['city']) ?></li>
                <li><strong>Pays :</strong> <?= htmlspecialchars($user['country']) ?></li>
                <li><strong>Compte créé le :</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?></li>
            </ul>
        </div>

        <?php elseif ($tab === 'invites'): ?>

        <?php require_once __DIR__ . '/partials/carnet-adresse.php'; ?>

        <?php endif; ?>


        <div class="account__actions">
            <a href="connexion.php" class="account__button account__button--danger">
                Se déconnecter
            </a>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
