<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

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
        <p>Projet backend PHP / MySQL / PDO</p>
    </div>
</header>
<?php require __DIR__ . '/menu.php'; ?>
<main>
    <div>
        <?php if ($flashSuccess !== null): ?>
            <p><?= e($flashSuccess) ?></p>
        <?php endif; ?>
        <?php if ($flashError !== null): ?>
            <p><?= e($flashError) ?></p>
        <?php endif; ?>
