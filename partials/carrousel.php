<?php if (!empty($categorieActuelle)): ?>
<section class="category-hero">
    <div class="category-hero-bg">
        <img src="<?= htmlspecialchars($categorieActuelle['carrousel']) ?>"
             alt="<?= htmlspecialchars($categorieActuelle['titre']) ?>">
    </div>

</section>
<?php endif; ?>