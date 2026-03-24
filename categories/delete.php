<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('editeur', 'administrateur');

requireValidPostWithCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = getPDO()->prepare('DELETE FROM categories WHERE id = :id');
    try {
        $stmt->execute(['id' => $id]);
        setFlash('success', 'Categorie supprimee avec succes.');
    } catch (PDOException) {
        setFlash('error', 'Impossible de supprimer une categorie deja utilisee par des articles.');
    }
}

redirect(url('/categories/index.php'));
