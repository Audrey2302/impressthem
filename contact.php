
<?php

// On inclut le fichier de connexion à la base de données
// → il crée la variable $pdo (connexion PDO à MySQL)
require_once __DIR__ . '/config/db.php';

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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact – Impress Them</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- ================= CONTACT ================= -->
<div class="container contact">
    <section class="contact-header text-center">
        <h1>Contactez-nous</h1>
        <p>
            Si vous souhaitez de plus amples informations ou si vous rencontrez des difficultés 
			lors de votre personnalisation de commandes, vous pouvez nous contacter par téléphone au 
			<strong>04&nbsp;99&nbsp;64&nbsp;13&nbsp;98</strong> du lundi au vendredi de 10h à 17h sans interruption, ou en utilisant le formulaire 
			de contact ci-dessous. Nous vous répondons sous 24h ouvrées. 
		
		
        </p>
    </section>

    <div class="contact-box">
        <form class="contact-form" method="post" action="https://impressthem.fr/contact">

            <div class="contact-row">
                <div class="contact-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="contact-field" placeholder="Votre nom" required>
                </div>

                <div class="contact-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="contact-field" placeholder="Votre prénom" required>
                </div>
            </div>

            <div class="contact-row">
                <div class="contact-group">
                    <label>Email</label>
                    <input type="email" name="adresse_mail" class="contact-field" placeholder="Votre email" required>
                </div>

                <div class="contact-group">
                    <label>Téléphone</label>
                    <input type="text" name="numero_telephone" class="contact-field" placeholder="Votre téléphone">
                </div>
            </div>

            <div class="contact-group">
                <label>Objet</label>
                <input type="text" name="objet" class="contact-field" placeholder="Objet du message" required>
            </div>

            <div class="contact-group">
                <label>Message</label>
                <textarea name="message" rows="6" class="contact-field" placeholder="Votre message"></textarea>
            </div>

            <div class="contact-captcha">
                <div class="contact-group">
                    <label>Recopiez le mot</label>
                    <img src="https://impressthem.fr/captcha" alt="Captcha">
                    <input type="text" name="captcha" class="contact-field" required>
                </div>
            </div>

            <div class="contact-error" id="js_message_erreur_captcha">
                Le captcha est incorrect. Merci de réessayer.
            </div>

            <div class="contact-actions">
                <button type="submit" class="contact-submit">Envoyer</button>
            </div>

</form>


        <p class="contact-legal">
            Conformément à la loi « informatique et libertés », vous disposez d’un droit
            d’accès et de suppression de vos données :
            <a href="mailto:service.client@impressthem.fr">service.client@impressthem.fr</a>
        </p>
    </div>
</div>


<!-- ================= FOOTER ================= -->
<?php require_once __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
