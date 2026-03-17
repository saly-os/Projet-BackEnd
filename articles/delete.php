<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

// Suppression protegee par methode POST + jeton CSRF.
requireValidPostWithCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // On supprime uniquement l'article demande.
    $stmt = getPDO()->prepare('DELETE FROM articles WHERE id = :id');
    $stmt->execute(['id' => $id]);
    setFlash('success', 'Article supprime avec succes.');
}

// Retour a la liste de gestion.
redirect(url('/articles/index.php'));
