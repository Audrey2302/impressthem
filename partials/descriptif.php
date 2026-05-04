<?php if (!empty($typeActuel)): ?>
<section class="type-hero">
    <div class="container">
        <h1><?= htmlspecialchars($typeActuel['LIBELLE']) ?></h1>
        <p><?= nl2br(htmlspecialchars($typeActuel['DESCRIPTION'])) ?></p>
    </div>
</section>
<?php endif; ?>
