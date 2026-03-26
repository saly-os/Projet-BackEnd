<?php

declare(strict_types=1);

$user = $user ?? [
    'prenom' => old('prenom'),
    'nom' => old('nom'),
    'login' => old('login'),
    'role' => old('role'),
];
?>
<form method="post" class="form-card form-grid" data-validate="true" novalidate>
    <div class="form-grid form-grid--two">
        <div class="form-group">
            <label for="prenom">Prenom</label>
            <input type="text" id="prenom" name="prenom" value="<?= e((string) $user['prenom']) ?>" data-required="true" data-label="prenom" data-error="prenomError">
            <p class="form-error" id="prenomError"><?= isset($errors['prenom']) ? e($errors['prenom']) : '' ?></p>
        </div>
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= e((string) $user['nom']) ?>" data-required="true" data-label="nom" data-error="nomError">
            <p class="form-error" id="nomError"><?= isset($errors['nom']) ? e($errors['nom']) : '' ?></p>
        </div>
    </div>
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" value="<?= e((string) $user['login']) ?>" data-required="true" data-label="login" data-error="loginError">
        <p class="form-error" id="loginError"><?= isset($errors['login']) ? e($errors['login']) : '' ?></p>
    </div>
    <div class="form-group">
        <label for="mot_de_passe">Mot de passe <?= isset($isEdit) && $isEdit ? '(laisser vide pour conserver)' : '' ?></label>
        <input type="password" id="mot_de_passe" name="mot_de_passe"<?= isset($isEdit) && $isEdit ? '' : ' data-required="true" data-label="mot de passe" data-error="passwordError"' ?>>
        <p class="form-error" id="passwordError"><?= isset($errors['mot_de_passe']) ? e($errors['mot_de_passe']) : '' ?></p>
    </div>
    <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role" data-required="true" data-label="role" data-error="roleError">
            <option value="">Choisir un role</option>
            <option value="editeur" <?= (string) $user['role'] === 'editeur' ? 'selected' : '' ?>>Editeur</option>
            <option value="administrateur" <?= (string) $user['role'] === 'administrateur' ? 'selected' : '' ?>>Administrateur</option>
        </select>
        <p class="form-error" id="roleError"><?= isset($errors['role']) ? e($errors['role']) : '' ?></p>
    </div>
    <button type="submit" class="btn-submit">Enregistrer</button>
</form>
