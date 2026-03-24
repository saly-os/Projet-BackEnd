<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

logoutUser();

$pageTitle = 'Deconnexion';

require __DIR__ . '/entete.php';
?>
<section class="logout-page">
    <div class="logout-box">
        <p class="page-tag">Session</p>
        <h2>Deconnexion</h2>
        <p>
            Vous avez ete deconnecte avec succes. Vous pouvez vous reconnecter a tout moment.
        </p>
    </div>

    <div class="message-box">
        <p>Votre session est maintenant fermee.</p>
        <a href="<?= e(url('/connexion.php')) ?>" class="btn btn--rose">Retour a la connexion</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
