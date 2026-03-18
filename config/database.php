<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

// Cree une unique connexion PDO reutilisable dans toute l'application.
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );

    $options = [
        // Remonte les erreurs SQL sous forme d'exceptions.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Retourne directement des tableaux associatifs.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Force l'utilisation de vraies requetes preparees MySQL.
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    return $pdo;
}
