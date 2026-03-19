<header class="site-header">
    <div class="site-header__container">
        <div class="site-brand">
            <h1 class="site-brand__title">Xibarru Dakar</h1>
            <p class="site-brand__subtitle">L'actualite au Senegal</p>
        </div>

        
    </div>
</header>

<main>
    <div class="alert alert--success">
        Message de succes
    </div>

    <div class="alert alert--error">
        Message d'erreur
    </div>


<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Permet a chaque page de definir son propre titre.
$pageTitle = $pageTitle ?? APP_NAME;
$flashSuccess = getFlash('success');
$flashError = getFlash('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
</head>
<body>
<header>
    <div>
        <h1><?= e(APP_NAME) ?></h1>
        <!-- Entete minimal conserve uniquement pour naviguer et tester le backend. -->
        <p>Projet backend PHP / MySQL / PDO</p>
    </div>
</header>
<?php require __DIR__ . '/menu.php'; ?>
<main>
    <div>
        <!-- Les messages flash servent a afficher le resultat d'une action apres redirection. -->
        <?php if ($flashSuccess !== null): ?>
            <p><?= e($flashSuccess) ?></p>
        <?php endif; ?>
        <?php if ($flashError !== null): ?>
            <p><?= e($flashError) ?></p>
        <?php endif; ?>
