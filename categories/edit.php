<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('editeur', 'administrateur');

$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('error', 'Categorie introuvable.');
    redirect(url('/categories/index.php'));
}

$stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
$stmt->execute(['id' => $id]);
$categorie = $stmt->fetch();

if (!$categorie) {
    setFlash('error', 'Categorie introuvable.');
    redirect(url('/categories/index.php'));
}

$pageTitle = 'Modifier une categorie';
$errors = [];

if (isPost()) {
    $data = ['nom' => trim((string) ($_POST['nom'] ?? ''))];
    $errors = validateRequired($data, ['nom' => 'nom']);

    if (!$errors) {
        if (valueExists('categories', 'nom', $data['nom'], $id)) {
            $errors['nom'] = 'Cette categorie existe deja.';
        }
    }

    if (!$errors) {
        $update = $pdo->prepare('UPDATE categories SET nom = :nom WHERE id = :id');
        $update->execute(['nom' => $data['nom'], 'id' => $id]);
        setFlash('success', 'Categorie modifiee avec succes.');
        redirect(url('/categories/index.php'));
    }

    $categorie = array_merge($categorie, $data);
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Modifier une categorie</h2>
    <?php require __DIR__ . '/form.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>

<form method="post" data-validate="true" novalidate>
    <div class="form-group">
        <label for="nom">Nom</label>
        <input
            type="text"
            id="nom"
            name="nom"
            data-required="true"
            data-label="nom"
            data-error="nomError"
            placeholder="Modifiez le nom de la categorie"
        >
        <p class="form-error" id="nomError"></p>
    </div>

    <button type="submit" class="btn btn--primary">Modifier la categorie</button>
</form>
