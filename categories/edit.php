<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

// Mise a jour d'une categorie existante.
$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// L'identifiant doit etre valide avant de poursuivre.
if (!$id) {
    setFlash('error', 'Categorie introuvable.');
    redirect(url('/categories/index.php'));
}

$stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
$stmt->execute(['id' => $id]);
$categorie = $stmt->fetch();

// Si la categorie n'existe pas, retour a la liste.
if (!$categorie) {
    setFlash('error', 'Categorie introuvable.');
    redirect(url('/categories/index.php'));
}

$pageTitle = 'Modifier une categorie';
$errors = [];

if (isPost()) {
    // Nettoyage de la nouvelle valeur.
    $data = ['nom' => trim((string) ($_POST['nom'] ?? ''))];
    $errors = validateRequired($data, ['nom' => 'nom']);

    if (!$errors) {
        // On autorise la valeur courante, mais pas celle d'une autre categorie.
        if (valueExists('categories', 'nom', $data['nom'], $id)) {
            $errors['nom'] = 'Cette categorie existe deja.';
        }
    }

    if (!$errors) {
        // Mise a jour de la seule colonne editable.
        $update = $pdo->prepare('UPDATE categories SET nom = :nom WHERE id = :id');
        $update->execute(['nom' => $data['nom'], 'id' => $id]);
        setFlash('success', 'Categorie modifiee avec succes.');
        redirect(url('/categories/index.php'));
    }

    // En cas d'erreur, on conserve la saisie de l'utilisateur.
    $categorie = array_merge($categorie, $data);
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Modifier une categorie</h2>
    <?php require __DIR__ . '/form.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
