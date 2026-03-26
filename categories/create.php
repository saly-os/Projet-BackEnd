<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('editeur', 'administrateur');

$pageTitle = 'Nouvelle categorie';
$errors = [];

if (isPost()) {
    $data = ['nom' => trim((string) ($_POST['nom'] ?? ''))];
    $errors = validateRequired($data, ['nom' => 'nom']);

    if (!$errors) {
        if (valueExists('categories', 'nom', $data['nom'])) {
            $errors['nom'] = 'Cette categorie existe deja.';
        }
    }

    if (!$errors) {
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
            placeholder="Entrez le nom de la categorie"
        >
        <p class="form-error" id="nomError"></p>
    </div>

    <button type="submit" class="btn btn--primary">Creer la categorie</button>
</form>

