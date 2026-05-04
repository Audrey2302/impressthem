<?php
// ⚠️ PAS de session_start ici (déjà dans mon-compte.php)

// Sécurité
if (!isset($_SESSION['user_id'])) {
    exit("Accès refusé 😅");
}

$userId = $_SESSION['user_id'];

/* =========================
   INSERT INVITÉ
========================= */
if (isset($_POST['add_invite'])) {

    $stmt = $pdo->prepare("
        INSERT INTO invites (user_id, prenom, nom, email, telephone)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $userId,
        $_POST['prenom'],
        $_POST['nom'],
        $_POST['email'] ?? null,
        $_POST['telephone'] ?? null
    ]);

    // ⚠️ recharge sur mon-compte avec onglet actif
    header("Location: mon-compte.php?tab=invites");
    exit;
}

/* =========================
   FETCH INVITÉS
========================= */
$stmt = $pdo->prepare("
    SELECT * FROM invites
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->execute([$userId]);
$invites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="guestbook">

    <!-- HEADER -->
    <div class="guestbook-header">
        <h1>Mes invités 💌</h1>

        <button class="btn-add" id="openModal">
            + Invité
        </button>
    </div>

    <div class="guestbook-container">

        <!-- LISTE -->
        <div class="guestbook-list">

            <?php if (empty($invites)): ?>
                <p>Aucun invité pour le moment 🥲</p>
            <?php endif; ?>

            <?php foreach ($invites as $invite): ?>
                <div class="guest-item">

                    <div>
                        <strong>
                            <?= htmlspecialchars($invite['prenom']) ?>
                            <?= htmlspecialchars($invite['nom']) ?>
                        </strong>
                    </div>

                    <span class="status <?= htmlspecialchars($invite['statut'] ?? 'en_attente') ?>">
                        <?= htmlspecialchars($invite['statut'] ?? 'En attente') ?>
                    </span>

                </div>
            <?php endforeach; ?>

        </div>

        <!-- DETAIL -->
        <div class="guestbook-detail">
            <p>Sélectionne un invité 👀</p>
        </div>

    </div>

</section>

<!-- MODAL -->
<div class="modal" id="modal">

    <div class="modal-content">

        <h2>Ajouter un invité</h2>

        <form method="POST">

            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="text" name="nom" placeholder="Nom" required>

            <input type="email" name="email" placeholder="Email">
            <input type="text" name="telephone" placeholder="Téléphone">

            <button type="submit" name="add_invite">
                Ajouter
            </button>

        </form>

    </div>

</div>

<script>
const modal = document.getElementById('modal');

const openBtn = document.getElementById('openModal');

if (openBtn) {
    openBtn.onclick = () => {
        modal.style.display = 'flex';
    };
}

if (modal) {
    modal.onclick = (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    };
}
</script>
