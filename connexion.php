<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

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
<section class="login-page">
    <div class="login-page__intro">
        <p class="login-page__tag">Espace membre</p>
        <h2>Connexion</h2>
        <p class="login-page__text">
            Connectez-vous pour acceder a votre espace et gerer vos actions selon votre role.
        </p>
    </div>

    <div class="login-card">
        <form method="post" class="login-form" id="loginForm" data-validate="true" novalidate>
    <div class="form-group">
        <label for="login">Login</label>
        <input
            type="text"
            id="login"
            name="login"
            data-required="true"
            data-label="login"
            data-error="loginError"
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
                data-required="true"
                data-label="mot de passe"
                data-error="passwordError"
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

<?php require __DIR__ . '/includes/footer.php'; ?>
