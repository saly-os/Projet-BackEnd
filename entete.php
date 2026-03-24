<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

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
    <link rel="stylesheet" href="<?= e(url('/style.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="site-header__container">
        <div class="site-brand">
            <h1 class="site-brand__title">Xibarru Dakar</h1>
            <p class="site-brand__subtitle">L'actualite au Senegal</p>
        </div>
        
    </div>
</header>
<?php require __DIR__ . '/menu.php'; ?>
<main>
    <div class="alert alert--success"<?= $flashSuccess === null ? ' style="display:none;"' : '' ?>>
        <?= e($flashSuccess ?? 'Message de succes') ?>
    </div>

    <div class="alert alert--error"<?= $flashError === null ? ' style="display:none;"' : '' ?>>
        <?= e($flashError ?? "Message d'erreur") ?>
    </div>
