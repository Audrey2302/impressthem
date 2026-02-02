<?php if (!empty($typeActuel)): ?>
<section class="type-hero">
    <div class="container">
        <h1><?= htmlspecialchars($typeActuel['titre']) ?></h1>
        <p><?= nl2br(htmlspecialchars($typeActuel['texte'])) ?></p>
    </div>
</section>
<?php endif; ?>
