<?php if (!empty($categorieActuelle)): ?>
<section class="category-hero">
    <div class="category-hero-bg">
        <img src="<?= htmlspecialchars($categorieActuelle['CARROUSEL']) ?>"
             alt="<?= htmlspecialchars($categorieActuelle['LIBELLE']) ?>">
    </div>

</section>
<?php endif; ?>