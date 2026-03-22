<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Tente une authentification avec verification du mot de passe hache.
function attemptLogin(string $login, string $password): bool
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, prenom, nom, login, mot_de_passe, role FROM utilisateurs WHERE login = :login LIMIT 1');
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['mot_de_passe'])) {
        return false;
    }

    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'prenom' => $user['prenom'],
        'nom' => $user['nom'],
        'login' => $user['login'],
        'role' => $user['role'],
    ];

    return true;
}

// Vide completement la session utilisateur.
function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
}
