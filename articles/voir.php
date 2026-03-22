<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('error', 'Article introuvable.');
    redirect(url('/accueil.php'));
}

$stmt = $pdo->prepare(
    'SELECT a.*, c.nom AS categorie, u.prenom, u.nom
     FROM articles a
     INNER JOIN categories c ON c.id = a.categorie_id
     INNER JOIN utilisateurs u ON u.id = a.auteur_id
     WHERE a.id = :id'
);
$stmt->execute(['id' => $id]);
$article = $stmt->fetch();

if (!$article) {
    setFlash('error', 'Article introuvable.');
    redirect(url('/accueil.php'));
}

$pageTitle = $article['titre'];
require __DIR__ . '/../entete.php';
?>
<article>
    <h2><?= e($article['titre']) ?></h2>
    <p>
        Categorie: <?= e($article['categorie']) ?> |
        Auteur: <?= e($article['prenom'] . ' ' . $article['nom']) ?> |
        Date: <?= e($article['date_publication']) ?>
    </p>
    <p><?= nl2br(e($article['contenu'])) ?></p>
</article>
<?php require __DIR__ . '/../includes/footer.php'; ?>
