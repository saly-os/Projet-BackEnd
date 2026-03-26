<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('editeur', 'administrateur');

$pdo = getPDO();
$pageTitle = 'Nouvel article';
$errors = [];

$categories = $pdo->query('SELECT id, nom FROM categories ORDER BY nom')->fetchAll();

if (isPost()) {
    $data = [
        'titre' => trim((string) ($_POST['titre'] ?? '')),
        'description_courte' => trim((string) ($_POST['description_courte'] ?? '')),
        'contenu' => trim((string) ($_POST['contenu'] ?? '')),
        'categorie_id' => trim((string) ($_POST['categorie_id'] ?? '')),
    ];

    $errors = validateRequired($data, [
        'titre' => 'titre',
        'description_courte' => 'description courte',
        'contenu' => 'contenu',
        'categorie_id' => 'categorie',
    ]);

    if (!$errors) {
        if (!categoryExists((int) $data['categorie_id'])) {
            $errors['categorie_id'] = 'Categorie invalide.';
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO articles (titre, description_courte, contenu, categorie_id, auteur_id, date_publication)
             VALUES (:titre, :description_courte, :contenu, :categorie_id, :auteur_id, NOW())'
        );
        $stmt->execute([
            'titre' => $data['titre'],
            'description_courte' => $data['description_courte'],
            'contenu' => $data['contenu'],
            'categorie_id' => (int) $data['categorie_id'],
            'auteur_id' => currentUser()['id'],
        ]);

        setFlash('success', 'Article ajoute avec succes.');
        redirect(url('/articles/index.php'));
    }
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Ajouter un article</h2>
    <?php require __DIR__ . '/form.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>

<form method="post" data-validate="true" novalidate>
    <div class="form-group">
        <label for="titre">Titre</label>
        <input
            type="text"
            id="titre"
            name="titre"
            data-required="true"
            data-label="titre"
            data-error="titreError"
            placeholder="Entrez le titre"
        >
        <p class="form-error" id="titreError"></p>
    </div>

    <div class="form-group">
        <label for="description_courte">Description courte</label>
        <textarea
            id="description_courte"
            name="description_courte"
            data-required="true"
            data-label="description courte"
            data-error="descriptionCourteError"
            placeholder="Entrez une courte description"
        ></textarea>
        <p class="form-error" id="descriptionCourteError"></p>
    </div>

    <div class="form-group">
        <label for="contenu">Contenu</label>
        <textarea
            id="contenu"
            name="contenu"
            data-required="true"
            data-label="contenu"
            data-error="contenuError"
            placeholder="Entrez le contenu de l'article"
        ></textarea>
        <p class="form-error" id="contenuError"></p>
    </div>

    <div class="form-group">
        <label for="categorie_id">Categorie</label>
        <select
            id="categorie_id"
            name="categorie_id"
            data-required="true"
            data-label="categorie"
            data-error="categorieError"
        >
            <option value="">Choisir une categorie</option>
        </select>
        <p class="form-error" id="categorieError"></p>
    </div>

    <button type="submit" class="btn btn--primary">Creer l'article</button>
</form>

