<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (isLoggedIn()) {
    redirect(url('/accueil.php'));
}

$pageTitle = 'Connexion';
$errors = [];

if (isPost()) {
    $login = trim((string) ($_POST['login'] ?? ''));
    $password = (string) ($_POST['mot_de_passe'] ?? '');

    $errors = validateRequired(
        ['login' => $login, 'mot_de_passe' => $password],
        ['login' => 'login', 'mot_de_passe' => 'mot de passe']
    );

    if (!$errors && attemptLogin($login, $password)) {
        setFlash('success', 'Connexion reussie.');
        redirect(url('/accueil.php'));
    }

    if (!$errors) {
        $errors['auth'] = 'Identifiants invalides.';
    }
}

require __DIR__ . '/entete.php';
?>
<section>
    <h2>Connexion</h2>
    <form method="post">
        <div>
            <label for="login">Login</label>
            <input type="text" id="login" name="login" value="<?= e(old('login')) ?>">
            <?php if (isset($errors['login'])): ?><p><?= e($errors['login']) ?></p><?php endif; ?>
        </div>
        <div>
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe">
            <?php if (isset($errors['mot_de_passe'])): ?><p><?= e($errors['mot_de_passe']) ?></p><?php endif; ?>
        </div>
        <?php if (isset($errors['auth'])): ?><p><?= e($errors['auth']) ?></p><?php endif; ?>
        <button type="submit">Se connecter</button>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
