<?php

declare(strict_types=1);
?>
<nav class="site-nav">
    <button type="button" class="site-nav__toggle" id="navToggle">Menu</button>

    <ul class="site-nav__list" id="navMenu">
        <li>
            <a href="<?= e(url('/accueil.php')) ?>" class="site-nav__link">Accueil</a>
        </li>

        <li>
            <a href="<?= e(url('/categories/index.php')) ?>" class="site-nav__link">Categories</a>
        </li>

        <?php if (hasRole('editeur', 'administrateur')): ?>
            <li>
                <a href="<?= e(url('/articles/index.php')) ?>" class="site-nav__link">Articles</a>
            </li>
            <li>
                <a href="<?= e(url('/articles/create.php')) ?>" class="site-nav__link">Publier</a>
            </li>
        <?php endif; ?>

        <?php if (hasRole('administrateur')): ?>
            <li>
                <a href="<?= e(url('/utilisateurs/index.php')) ?>" class="site-nav__link">Utilisateurs</a>
            </li>
        <?php endif; ?>

        <?php if (isLoggedIn()): ?>
            <li>
                <span class="site-nav__link">Connecte : <?= e(currentUser()['prenom'] . ' ' . currentUser()['nom']) ?> (<?= e(currentUser()['role']) ?>)</span>
            </li>

            <li>
                <a href="<?= e(url('/deconnexion.php')) ?>" class="site-nav__link site-nav__link--button">Deconnexion</a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?= e(url('/connexion.php')) ?>" class="site-nav__link site-nav__link--button">Connexion</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
