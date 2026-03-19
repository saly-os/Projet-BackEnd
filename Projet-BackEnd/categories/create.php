<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

// Creation d'une nouvelle categorie.
$pageTitle = 'Nouvelle categorie';
$errors = [];

if (isPost()) {
    // Lecture et nettoyage de la saisie.
    $data = ['nom' => trim((string) ($_POST['nom'] ?? ''))];
    $errors = validateRequired($data, ['nom' => 'nom']);

    if (!$errors) {
        // Une categorie ne doit pas etre dupliquee.
        if (valueExists('categories', 'nom', $data['nom'])) {
            $errors['nom'] = 'Cette categorie existe deja.';
        }
    }

    if (!$errors) {
        // Insertion simple avec requete preparee.
        $stmt = getPDO()->prepare('INSERT INTO categories (nom) VALUES (:nom)');
        $stmt->execute(['nom' => $data['nom']]);
        setFlash('success', 'Categorie ajoutee avec succes.');
        redirect(url('/categories/index.php'));
    }
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Ajouter une categorie</h2>
    <?php require __DIR__ . '/form.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
