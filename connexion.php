<?php

// ==============================
// 1️⃣ Connexion à la base de données
// ==============================

// On inclut le fichier db.php
// Ce fichier contient la connexion PDO à MySQL
// Il crée une variable appelée $pdo
require_once __DIR__ . '/config/db.php';


// ==============================
// 2️⃣ Démarrage de la session
// ==============================

// Les sessions servent à mémoriser l'utilisateur connecté
// Sans session_start(), $_SESSION ne fonctionne pas
session_start();


// ==============================
// 3️⃣ Variable pour stocker les erreurs
// ==============================

// Si la connexion échoue, on affichera un message d'erreur
$error = null;


// ==============================
// 4️⃣ Vérifier si le formulaire a été envoyé
// ==============================

// $_SERVER['REQUEST_METHOD'] contient le type de requête HTTP
// Ici, on vérifie que le formulaire a été envoyé en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ==============================
    // 5️⃣ Récupération des données du formulaire
    // ==============================

    // trim() enlève les espaces au début et à la fin
    $email = trim($_POST['adresse_email'] ?? '');

    // On récupère le mot de passe saisi
    $password = $_POST['mot_de_passe'] ?? '';


    // ==============================
    // 6️⃣ Vérification basique des champs
    // ==============================

    // On vérifie que les champs ne sont pas vides
    if ($email && $password) {

        // ==============================
        // 7️⃣ Recherche de l'utilisateur en base de données
        // ==============================

        // On prépare une requête SQL sécurisée
        // ? sera remplacé par la valeur de $email
        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        // On exécute la requête avec l'email de l'utilisateur
        $stmt->execute([$email]);

        // On récupère la ligne trouvée (ou false si rien trouvé)
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // ==============================
        // 8️⃣ Vérification du mot de passe
        // ==============================

        // password_verify compare :
        // - le mot de passe tapé par l'utilisateur
        // - le mot de passe hashé stocké en base
        if ($user && password_verify($password, $user['password'])) {

            // ==============================
            // 9️⃣ Connexion réussie → on crée la session
            // ==============================

            // On stocke l'ID utilisateur en session
            $_SESSION['user_id'] = $user['id'];

            // On stocke aussi le nom d'utilisateur
            $_SESSION['username'] = $user['username'];

            // Redirection vers la page d'accueil
            header('Location: index.php');
            exit;

        } else {

            // ==============================
            // ❌ Mauvais email ou mot de passe
            // ==============================

            $error = "Adresse e-mail ou mot de passe incorrect.";
        }

    } else {

        // ==============================
        // ❌ Champs non remplis
        // ==============================

        $error = "Veuillez remplir tous les champs.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<?php require_once __DIR__ . '/partials/header.php'; ?>

<main class="login">
    <div class="login__container">
        <h1 class="login__title">Bienvenue</h1>
        <p class="login__subtitle">Déjà membre ? Connectez-vous</p>

        <?php if ($error): ?>
            <div class="login__error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="login__form">

            <div class="login__field">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="adresse_email" required>
            </div>

            <div class="login__field">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="mot_de_passe" required>
            </div>

            <button type="submit" class="login__button">
                Connexion
            </button>
        </form>

        <div class="login__links">
            <a href="inscription.php">Pas encore membre ? Inscrivez-vous</a>
            <a href="mot-de-passe-oublie.php">Mot de passe oublié ?</a>
        </div>
    </div>
</main>

<!-- ================= FOOTER ================= -->
<?php require_once __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
