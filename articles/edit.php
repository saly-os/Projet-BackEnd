<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('editeur', 'administrateur');

$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('error', 'Article introuvable.');
    redirect(url('/articles/index.php'));
}

$stmt = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
$stmt->execute(['id' => $id]);
$article = $stmt->fetch();

if (!$article) {
    setFlash('error', 'Article introuvable.');
    redirect(url('/articles/index.php'));
}

$isAdmin = hasRole('administrateur');
$user = currentUser();
if (!$isAdmin && (int) $article['auteur_id'] !== (int) $user['id']) {
    setFlash('error', 'Vous ne pouvez modifier que vos propres articles.');
    redirect(url('/articles/index.php'));
}

$pageTitle = 'Modifier un article';
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
        $update = $pdo->prepare(
            'UPDATE articles
             SET titre = :titre, description_courte = :description_courte, contenu = :contenu, categorie_id = :categorie_id
             WHERE id = :id'
        );
        $update->execute([
            'titre' => $data['titre'],
            'description_courte' => $data['description_courte'],
            'contenu' => $data['contenu'],
            'categorie_id' => (int) $data['categorie_id'],
            'id' => $id,
        ]);

        setFlash('success', 'Article modifie avec succes.');
        redirect(url('/articles/index.php'));
    }

    $article = array_merge($article, $data);
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Modifier un article</h2>
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
            placeholder="Modifiez le titre"
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
            placeholder="Modifiez la description courte"
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
            placeholder="Modifiez le contenu"
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

    <button type="submit" class="btn btn--primary">Modifier l'article</button>
</form>
