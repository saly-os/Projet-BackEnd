<?php

declare(strict_types=1);

// Formulaire mutualise pour creation et modification d'utilisateur.
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
            <!-- Informations d'identite affichees dans l'interface et utiles a l'administration. -->
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
        <!-- Le login sert a l'authentification et doit rester unique. -->
        <label for="login">Login</label>
        <input type="text" id="login" name="login" value="<?= e((string) $user['login']) ?>">
        <?php if (isset($errors['login'])): ?><p><?= e($errors['login']) ?></p><?php endif; ?>
    </div>
    <div>
        <!-- En modification, le champ vide signifie qu'on conserve l'ancien mot de passe. -->
        <label for="mot_de_passe">Mot de passe <?= isset($isEdit) && $isEdit ? '(laisser vide pour conserver)' : '' ?></label>
        <input type="password" id="mot_de_passe" name="mot_de_passe">
        <?php if (isset($errors['mot_de_passe'])): ?><p><?= e($errors['mot_de_passe']) ?></p><?php endif; ?>
    </div>
    <div>
        <!-- Le role pilote les droits dans tout le backend. -->
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
