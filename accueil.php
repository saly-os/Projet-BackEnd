<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/config/database.php';

$pdo = getPDO();
$pageTitle = 'Accueil';
$categoryId = filter_input(INPUT_GET, 'categorie', FILTER_VALIDATE_INT) ?: null;
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$perPage = 5;

if ($categoryId) {
    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE categorie_id = :categorie_id');
    $countStmt->execute(['categorie_id' => $categoryId]);
} else {
    $countStmt = $pdo->query('SELECT COUNT(*) FROM articles');
}

$total = (int) $countStmt->fetchColumn();
$pagination = paginate($total, $perPage, $page);

if ($categoryId) {
    $stmt = $pdo->prepare(
        'SELECT a.id, a.titre, a.description_courte, a.date_publication, c.nom AS categorie, u.prenom, u.nom
         FROM articles a
         INNER JOIN categories c ON c.id = a.categorie_id
         INNER JOIN utilisateurs u ON u.id = a.auteur_id
         WHERE a.categorie_id = :categorie_id
         ORDER BY a.date_publication DESC
         LIMIT :limit OFFSET :offset'
    );
    $stmt->bindValue(':categorie_id', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare(
        'SELECT a.id, a.titre, a.description_courte, a.date_publication, c.nom AS categorie, u.prenom, u.nom
         FROM articles a
         INNER JOIN categories c ON c.id = a.categorie_id
         INNER JOIN utilisateurs u ON u.id = a.auteur_id
         ORDER BY a.date_publication DESC
         LIMIT :limit OFFSET :offset'
    );
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
    $stmt->execute();
}

$articles = $stmt->fetchAll();
$categories = $pdo->query('SELECT id, nom FROM categories ORDER BY nom')->fetchAll();

require __DIR__ . '/entete.php';
?>
<section>
    <h2>Derniers articles</h2>
    <form method="get">
        <div>
            <label for="categorie">Filtrer par categorie</label>
            <select name="categorie" id="categorie">
                <option value="">Toutes les categories</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= e((string) $categorie['id']) ?>" <?= $categoryId === (int) $categorie['id'] ? 'selected' : '' ?>>
                        <?= e($categorie['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button type="submit">Filtrer</button>
            <a href="<?= e(url('/accueil.php')) ?>">Reinitialiser</a>
        </div>
    </form>
</section>

<?php if (!$articles): ?>
    <div>
        <p>Aucun article trouve.</p>
    </div>
<?php endif; ?>

<?php foreach ($articles as $article): ?>
    <article>
        <h3>
            <a href="<?= e(url('/articles/voir.php?id=' . (string) $article['id'])) ?>"><?= e($article['titre']) ?></a>
        </h3>
        <p>
            Categorie: <?= e($article['categorie']) ?> |
            Auteur: <?= e($article['prenom'] . ' ' . $article['nom']) ?> |
            Date: <?= e($article['date_publication']) ?>
        </p>
        <p><?= nl2br(e($article['description_courte'])) ?></p>
    </article>
<?php endforeach; ?>

<div>
    <div>
        <?php if ($pagination['has_prev']): ?>
            <a href="<?= e(url('/accueil.php?page=' . $pagination['prev'] . ($categoryId ? '&categorie=' . $categoryId : ''))) ?>">Precedent</a>
        <?php endif; ?>
        <?php if ($pagination['has_next']): ?>
            <a href="<?= e(url('/accueil.php?page=' . $pagination['next'] . ($categoryId ? '&categorie=' . $categoryId : ''))) ?>">Suivant</a>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
