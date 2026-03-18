<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

// Creation d'un article par un utilisateur autorise.
$pdo = getPDO();
$pageTitle = 'Nouvel article';
$errors = [];

// Recupere les categories pour alimenter la liste deroulante.
$categories = $pdo->query('SELECT id, nom FROM categories ORDER BY nom')->fetchAll();

if (isPost()) {
    // Nettoie les donnees recues avant validation.
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
        // Verifie qu'on ne reference pas une categorie inexistante.
        if (!categoryExists((int) $data['categorie_id'])) {
            $errors['categorie_id'] = 'Categorie invalide.';
        }
    }

    if (!$errors) {
        // L'auteur est l'utilisateur actuellement connecte.
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
