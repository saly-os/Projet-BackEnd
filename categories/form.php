<?php

declare(strict_types=1);

$categorie = $categorie ?? ['nom' => old('nom')];
?>
<form method="post" class="form-card form-grid" data-validate="true" novalidate>
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= e((string) $categorie['nom']) ?>" data-required="true" data-label="nom" data-error="nomError">
        <p class="form-error" id="nomError"><?= isset($errors['nom']) ? e($errors['nom']) : '' ?></p>
    </div>
    <button type="submit" class="btn-submit">Enregistrer</button>
</form>
