<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// La page de connexion est reservee aux utilisateurs non authentifies.
if (isLoggedIn()) {
    redirect(url('/accueil.php'));
}

$pageTitle = 'Connexion';
$errors = [];

if (isPost()) {
    // Le login est nettoye, le mot de passe est lu tel quel pour la verification du hash.
    $login = trim((string) ($_POST['login'] ?? ''));
    $password = (string) ($_POST['mot_de_passe'] ?? '');

    $errors = validateRequired(
        ['login' => $login, 'mot_de_passe' => $password],
        ['login' => 'login', 'mot_de_passe' => 'mot de passe']
    );

    if (!$errors && attemptLogin($login, $password)) {
        // En cas de succes, l'utilisateur est redirige vers l'accueil.
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
    <!-- Le controle client est laisse au membre charge du JavaScript. -->
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
