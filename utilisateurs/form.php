<?php

declare(strict_types=1);

$user = $user ?? [
    'prenom' => old('prenom'),
    'nom' => old('nom'),
    'login' => old('login'),
    'role' => old('role'),
];
?>
<form method="post">
    <div>
        <div>
            <label for="prenom">Prenom</label>
            <input type="text" id="prenom" name="prenom" value="<?= e((string) $user['prenom']) ?>">
            <?php if (isset($errors['prenom'])): ?><p><?= e($errors['prenom']) ?></p><?php endif; ?>
        </div>
        <div>
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= e((string) $user['nom']) ?>">
            <?php if (isset($errors['nom'])): ?><p><?= e($errors['nom']) ?></p><?php endif; ?>
        </div>
    </div>
    <div>
        <label for="login">Login</label>
        <input type="text" id="login" name="login" value="<?= e((string) $user['login']) ?>">
        <?php if (isset($errors['login'])): ?><p><?= e($errors['login']) ?></p><?php endif; ?>
    </div>
    <div>
        <label for="mot_de_passe">Mot de passe <?= isset($isEdit) && $isEdit ? '(laisser vide pour conserver)' : '' ?></label>
        <input type="password" id="mot_de_passe" name="mot_de_passe">
        <?php if (isset($errors['mot_de_passe'])): ?><p><?= e($errors['mot_de_passe']) ?></p><?php endif; ?>
    </div>
    <div>
        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="">Choisir un role</option>
            <option value="editeur" <?= (string) $user['role'] === 'editeur' ? 'selected' : '' ?>>Editeur</option>
            <option value="administrateur" <?= (string) $user['role'] === 'administrateur' ? 'selected' : '' ?>>Administrateur</option>
        </select>
        <?php if (isset($errors['role'])): ?><p><?= e($errors['role']) ?></p><?php endif; ?>
    </div>
    <button type="submit">Enregistrer</button>
</form>
