<?php

declare(strict_types=1);

$categorie = $categorie ?? ['nom' => old('nom')];
?>
<form method="post" class="form-card form-grid">
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= e((string) $categorie['nom']) ?>">
        <?php if (isset($errors['nom'])): ?><p class="form-error"><?= e($errors['nom']) ?></p><?php endif; ?>
    </div>
    <button type="submit" class="btn-submit">Enregistrer</button>
</form>
