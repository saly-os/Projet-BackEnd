<nav class="site-nav">
    <button type="button" class="site-nav__toggle" id="navToggle">Menu</button>

    <ul class="site-nav__list" id="navMenu">
        <li>
            <a href="#" class="site-nav__link">Accueil</a>
        </li>

        <li>
            <a href="#" class="site-nav__link">Categories</a>
        </li>

        <li>
            <a href="#" class="site-nav__link">Articles</a>
        </li>

        <li>
            <a href="#" class="site-nav__link">Utilisateurs</a>
        </li>

        <li>
            <span class="site-nav__link">Connecte : Jean Dupont (administrateur)</span>
        </li>

        <li>
            <a href="#" class="site-nav__link site-nav__link--button">Deconnexion</a>
        </li>
    </ul>
</nav>


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
