<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('administrateur');

// Suppression de compte protegee par POST et CSRF.
requireValidPostWithCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id && $id !== (int) currentUser()['id']) {
    // On interdit deja la suppression de soi-meme dans l'interface et ici cote serveur.
    $stmt = getPDO()->prepare('DELETE FROM utilisateurs WHERE id = :id');
    $stmt->execute(['id' => $id]);
    setFlash('success', 'Utilisateur supprime avec succes.');
}

// Retour a la liste d'administration.
redirect(url('/utilisateurs/index.php'));
