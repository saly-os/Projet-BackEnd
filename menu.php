<?php

declare(strict_types=1);
?>
<nav>
    <div>
        <!-- Le menu varie selon le role de l'utilisateur connecte. -->
        <a href="<?= e(url('/accueil.php')) ?>">Accueil</a>
        |
        <a href="<?= e(url('/categories/index.php')) ?>">Categories</a>
        <?php if (hasRole('editeur', 'administrateur')): ?>
            |
            <!-- Les visiteurs n'ont pas acces a la gestion du contenu. -->
            <a href="<?= e(url('/articles/index.php')) ?>">Articles</a>
        <?php endif; ?>
        <?php if (hasRole('administrateur')): ?>
            |
            <!-- La gestion des comptes est strictement reservee a l'administrateur. -->
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
