<?php

declare(strict_types=1);

// Ouvre la session avant tout acces aux donnees utilisateur.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charge les constantes globales, fonctions utilitaires et logique d'authentification.
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
