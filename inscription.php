<?php
require_once __DIR__ . '/config/db.php';
session_start();

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $lastname   = trim($_POST['lastname'] ?? '');
    $firstname  = trim($_POST['firstname'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';
    $phone      = trim($_POST['phone'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $address2   = trim($_POST['address2'] ?? '');
    $postal     = trim($_POST['postal_code'] ?? '');
    $city       = trim($_POST['city'] ?? '');
    $country    = trim($_POST['country'] ?? '');

    if (
        $lastname && $firstname && $email && $password && $confirm &&
        $address && $postal && $city && $country
    ) {
        if ($password !== $confirm) {
            $error = "Les mots de passe ne correspondent pas 🙈";
        } else {

            $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$email]);

            if ($check->fetch()) {
                $error = "Cette adresse e-mail est déjà utilisée 😬";
            } else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO users
                    (firstname, lastname, email, password, phone, address, address_complement, postal_code, city, country)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $firstname,
                    $lastname,
                    $email,
                    $hashedPassword,
                    $phone,
                    $address,
                    $address2,
                    $postal,
                    $city,
                    $country
                ]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_email'] = $email;

                header('Location: mon-compte.php');
                exit;
            }
        }
    } else {
        $error = "Merci de remplir tous les champs obligatoires ✍️";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- ================= PAGE ================= -->

<main class="login">
  <div class="login__container login__container--large">
    <h1 class="login__title">Inscription</h1>
    <p class="login__subtitle">Rejoignez l’aventure 🚀</p>

    <?php if ($error): ?>
      <div class="login__error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="login__form">

      <div class="login__field">
        <label>Nom</label>
        <input type="text" name="lastname" required>
      </div>

      <div class="login__field">
        <label>Prénom</label>
        <input type="text" name="firstname" required>
      </div>

      <div class="login__field">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="login__field">
        <label>Téléphone</label>
        <input type="text" name="phone">
      </div>

      <div class="login__field">
        <label>Mot de passe</label>
        <input type="password" name="password" required>
      </div>

      <div class="login__field">
        <label>Confirmation du mot de passe</label>
        <input type="password" name="confirm_password" required>
      </div>

      <div class="login__field">
        <label>Adresse</label>
        <input type="text" name="address" required>
      </div>

      <div class="login__field">
        <label>Complément d’adresse</label>
        <input type="text" name="address2">
      </div>

      <div class="login__field">
        <label>Code postal</label>
        <input type="text" name="postal_code" required>
      </div>

      <div class="login__field">
        <label>Ville</label>
        <input type="text" name="city" required>
      </div>

      <div class="login__field">
        <label>Pays</label>
        <input type="text" name="country" required>
      </div>

      <button class="login__button">Créer mon compte ✨</button>
    </form>
  </div>
</main>


<!-- ================= FOOTER ================= -->
<?php require_once __DIR__ . '/partials/footer.php'; ?>

</body>
</html>