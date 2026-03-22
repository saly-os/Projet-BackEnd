<section class="login-page">
    <div class="login-page__intro">
        <p class="login-page__tag">Espace membre</p>
        <h2>Connexion</h2>
        <p class="login-page__text">
            Connectez-vous pour acceder a votre espace et gerer vos actions selon votre role.
        </p>
    </div>

    <div class="login-card">
        <form method="post" class="login-form" id="loginForm" novalidate>
            <div class="form-group">
                <label for="login">Login</label>
                <input
                    type="text"
                    id="login"
                    name="login"
                    placeholder="Entrez votre login"
                >
                <p class="form-error" id="loginError"></p>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <div class="password-field">
                    <input
                        type="password"
                        id="mot_de_passe"
                        name="mot_de_passe"
                        placeholder="Entrez votre mot de passe"
                    >
                    <button type="button" class="toggle-password" id="togglePassword">Afficher</button>
                </div>
                <p class="form-error" id="passwordError"></p>
            </div>

            <p class="form-error form-error--global" id="authError"></p>

            <button type="submit" class="btn-submit">Se connecter</button>
        </form>
    </div>
</section>


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
