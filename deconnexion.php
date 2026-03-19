<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

logoutUser();
session_start();
setFlash('success', 'Vous etes deconnecte.');
redirect(url('/connexion.php'));
