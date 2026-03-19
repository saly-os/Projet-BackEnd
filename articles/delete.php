<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

requireValidPostWithCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = getPDO()->prepare('DELETE FROM articles WHERE id = :id');
    $stmt->execute(['id' => $id]);
    setFlash('success', 'Article supprime avec succes.');
}

redirect(url('/articles/index.php'));
