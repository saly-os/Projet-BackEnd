<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

// Suppression de categorie protegee par POST et CSRF.
requireValidPostWithCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = getPDO()->prepare('DELETE FROM categories WHERE id = :id');
    try {
        // La suppression peut echouer si des articles referencent encore cette categorie.
        $stmt->execute(['id' => $id]);
        setFlash('success', 'Categorie supprimee avec succes.');
    } catch (PDOException) {
        setFlash('error', 'Impossible de supprimer une categorie deja utilisee par des articles.');
    }
}

// Retour a la liste apres traitement.
redirect(url('/categories/index.php'));
