<?php

declare(strict_types=1);

$article = $article ?? [
    'titre' => old('titre'),
    'description_courte' => old('description_courte'),
    'contenu' => old('contenu'),
    'categorie_id' => old('categorie_id'),
];
?>
<form method="post" class="form-card form-grid" data-validate="true" novalidate>
    <div class="form-group">
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" value="<?= e((string) $article['titre']) ?>" data-required="true" data-label="titre" data-error="titreError">
        <p class="form-error" id="titreError"><?= isset($errors['titre']) ? e($errors['titre']) : '' ?></p>
    </div>
    <div class="form-group">
        <label for="description_courte">Description courte</label>
        <textarea id="description_courte" name="description_courte" data-required="true" data-label="description courte" data-error="descriptionCourteError"><?= e((string) $article['description_courte']) ?></textarea>
        <p class="form-error" id="descriptionCourteError"><?= isset($errors['description_courte']) ? e($errors['description_courte']) : '' ?></p>
    </div>
    <div class="form-group">
        <label for="contenu">Contenu</label>
        <textarea id="contenu" name="contenu" data-required="true" data-label="contenu" data-error="contenuError"><?= e((string) $article['contenu']) ?></textarea>
        <p class="form-error" id="contenuError"><?= isset($errors['contenu']) ? e($errors['contenu']) : '' ?></p>
    </div>
    <div class="form-group">
        <label for="categorie_id">Categorie</label>
        <select id="categorie_id" name="categorie_id" data-required="true" data-label="categorie" data-error="categorieError">
            <option value="">Choisir une categorie</option>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= e((string) $categorie['id']) ?>" <?= (string) $article['categorie_id'] === (string) $categorie['id'] ? 'selected' : '' ?>>
                    <?= e($categorie['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="form-error" id="categorieError"><?= isset($errors['categorie_id']) ? e($errors['categorie_id']) : '' ?></p>
    </div>
    <button type="submit" class="btn-submit">Enregistrer</button>
</form>
