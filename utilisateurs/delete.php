<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('administrateur');

requireValidPostWithCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id && $id !== (int) currentUser()['id']) {
    $stmt = getPDO()->prepare('DELETE FROM utilisateurs WHERE id = :id');
    $stmt->execute(['id' => $id]);
    setFlash('success', 'Utilisateur supprime avec succes.');
}

redirect(url('/utilisateurs/index.php'));
