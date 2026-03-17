<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// Met fin a la session puis recree une session vide pour y stocker un flash message.
logoutUser();
session_start();
setFlash('success', 'Vous etes deconnecte.');
redirect(url('/connexion.php'));
