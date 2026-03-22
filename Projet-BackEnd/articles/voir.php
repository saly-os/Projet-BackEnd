<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

// Affiche le detail public d'un article.
$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Un id invalide n'autorise pas l'acces a la page.
if (!$id) {
    setFlash('error', 'Article introuvable.');
    redirect(url('/accueil.php'));
}

$stmt = $pdo->prepare(
    // On joint categorie et auteur pour afficher le contexte complet de l'article.
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
    <!-- Toutes les sorties passent par e() pour eviter le XSS. -->
    <h2><?= e($article['titre']) ?></h2>
    <p>
        Categorie: <?= e($article['categorie']) ?> |
        Auteur: <?= e($article['prenom'] . ' ' . $article['nom']) ?> |
        Date: <?= e($article['date_publication']) ?>
    </p>
    <p><?= nl2br(e($article['contenu'])) ?></p>
</article>
<?php require __DIR__ . '/../includes/footer.php'; ?>
