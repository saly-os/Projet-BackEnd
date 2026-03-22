<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

requireValidPostWithCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $pdo = getPDO();
    $authorStmt = $pdo->prepare('SELECT auteur_id FROM articles WHERE id = :id LIMIT 1');
    $authorStmt->execute(['id' => $id]);
    $article = $authorStmt->fetch();

    if (!$article) {
        setFlash('error', 'Article introuvable.');
        redirect(url('/articles/index.php'));
    }

    $isAdmin = hasRole('administrateur');
    $user = currentUser();
    if (!$isAdmin && (int) $article['auteur_id'] !== (int) $user['id']) {
        setFlash('error', 'Vous ne pouvez supprimer que vos propres articles.');
        redirect(url('/articles/index.php'));
    }

    $deleteStmt = $pdo->prepare('DELETE FROM articles WHERE id = :id');
    $deleteStmt->execute(['id' => $id]);
    setFlash('success', 'Article supprime avec succes.');
}

redirect(url('/articles/index.php'));
