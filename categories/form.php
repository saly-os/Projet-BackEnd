<?php

declare(strict_types=1);

$categorie = $categorie ?? ['nom' => old('nom')];
?>
<form method="post">
    <div>
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= e((string) $categorie['nom']) ?>">
        <?php if (isset($errors['nom'])): ?><p><?= e($errors['nom']) ?></p><?php endif; ?>
    </div>
    <button type="submit">Enregistrer</button>
</form>
