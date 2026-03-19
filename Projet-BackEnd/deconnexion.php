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
        <a href="#" class="btn btn--rose">Retour a la connexion</a>
    </div>
</section>


<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Met fin a la session puis recree une session vide pour y stocker un flash message.
logoutUser();
session_start();
setFlash('success', 'Vous etes deconnecte.');
redirect(url('/connexion.php'));
