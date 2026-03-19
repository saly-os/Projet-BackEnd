<?php

declare(strict_types=1);
?>
<nav>
    <div>
        <a href="<?= e(url('/accueil.php')) ?>">Accueil</a>
        |
        <a href="<?= e(url('/categories/index.php')) ?>">Categories</a>
        <?php if (hasRole('editeur', 'administrateur')): ?>
            |
            <a href="<?= e(url('/articles/index.php')) ?>">Articles</a>
        <?php endif; ?>
        <?php if (hasRole('administrateur')): ?>
            |
            <a href="<?= e(url('/utilisateurs/index.php')) ?>">Utilisateurs</a>
        <?php endif; ?>
        <?php if (isLoggedIn()): ?>
            |
            <span>
                Connecte: <?= e(currentUser()['prenom'] . ' ' . currentUser()['nom']) ?> (<?= e(currentUser()['role']) ?>)
            </span>
            |
            <a href="<?= e(url('/deconnexion.php')) ?>">Deconnexion</a>
        <?php else: ?>
            |
            <a href="<?= e(url('/connexion.php')) ?>">Connexion</a>
        <?php endif; ?>
    </div>
</nav>
