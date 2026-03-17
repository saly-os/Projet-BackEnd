<?php

declare(strict_types=1);

// Formulaire mutualise pour la creation et la modification d'article.
$article = $article ?? [
    'titre' => old('titre'),
    'description_courte' => old('description_courte'),
    'contenu' => old('contenu'),
    'categorie_id' => old('categorie_id'),
];
?>
<form method="post">
    <div>
        <!-- Titre principal de l'article. -->
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" value="<?= e((string) $article['titre']) ?>">
        <?php if (isset($errors['titre'])): ?><p><?= e($errors['titre']) ?></p><?php endif; ?>
    </div>
    <div>
        <!-- Resume court affiche dans la liste publique. -->
        <label for="description_courte">Description courte</label>
        <textarea id="description_courte" name="description_courte"><?= e((string) $article['description_courte']) ?></textarea>
        <?php if (isset($errors['description_courte'])): ?><p><?= e($errors['description_courte']) ?></p><?php endif; ?>
    </div>
    <div>
        <!-- Contenu integral visible dans la page de detail. -->
        <label for="contenu">Contenu</label>
        <textarea id="contenu" name="contenu"><?= e((string) $article['contenu']) ?></textarea>
        <?php if (isset($errors['contenu'])): ?><p><?= e($errors['contenu']) ?></p><?php endif; ?>
    </div>
    <div>
        <!-- La categorie est choisie parmi les categories existantes en base. -->
        <label for="categorie_id">Categorie</label>
        <select id="categorie_id" name="categorie_id">
            <option value="">Choisir une categorie</option>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= e((string) $categorie['id']) ?>" <?= (string) $article['categorie_id'] === (string) $categorie['id'] ? 'selected' : '' ?>>
                    <?= e($categorie['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['categorie_id'])): ?><p><?= e($errors['categorie_id']) ?></p><?php endif; ?>
    </div>
    <!-- Le bouton est partage entre creation et modification. -->
    <button type="submit">Enregistrer</button>
</form>
