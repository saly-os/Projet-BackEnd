<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';
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
<section class="home-hero">
    <div class="home-hero__content">
        <p class="home-hero__tag">Xibarru Dakar</p>
        <h2>Derniers articles</h2>
        <p class="home-hero__text">
            Decouvrez les dernieres actualites de Dakar, classees par categorie.

        </p>
    </div>
</section>

<section class="home-filter">
    <form method="get" class="filter-form" id="filterForm">
        <div class="filter-form__group">
            <label for="categorie">Filtrer par categorie</label>
            <select id="categorie" name="categorie">
                <option value="">Toutes les categories</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= e((string) $categorie['id']) ?>" <?= $categoryId === (int) $categorie['id'] ? 'selected' : '' ?>>
                        <?= e($categorie['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-form__actions">
            <button type="submit" class="btn btn--primary">Filtrer</button>
            <a href="<?= e(url('/accueil.php')) ?>" class="btn btn--secondary">Reinitialiser</a>
        </div>
    </form>
</section>

<section class="articles">
    <div class="articles__header">
        <h3>Articles recents</h3>
        <p><?= e((string) $total) ?> articles trouves</p>
    </div>

    <?php if (!$articles): ?>
        <section class="empty-state">
            <h3>Aucun article trouve</h3>
            <p>Essaie une autre categorie ou ajoute du contenu depuis l'espace editeur.</p>
        </section>
    <?php else: ?>
        <div class="articles__grid">
            <?php foreach ($articles as $article): ?>
                <article class="article-card">
                    <div class="article-card__top">
                        <span class="badge"><?= e($article['categorie']) ?></span>
                        <span class="article-card__date"><?= e($article['date_publication']) ?></span>
                    </div>

                    <h3 class="article-card__title">
                        <a href="<?= e(url('/articles/voir.php?id=' . (string) $article['id'])) ?>"><?= e($article['titre']) ?></a>
                    </h3>

                    <p class="article-card__author">Par <?= e($article['prenom'] . ' ' . $article['nom']) ?></p>

                    <p class="article-card__excerpt">
                        <?= nl2br(e($article['description_courte'])) ?>
                    </p>

                    <a href="<?= e(url('/articles/voir.php?id=' . (string) $article['id'])) ?>" class="article-card__link">Lire l'article</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<nav class="pagination" aria-label="Pagination">
    <?php if ($pagination['has_prev']): ?>
        <a href="<?= e(url('/accueil.php?page=' . $pagination['prev'] . ($categoryId ? '&categorie=' . $categoryId : ''))) ?>" class="pagination__btn">Precedent</a>
    <?php endif; ?>
    <span class="pagination__info">Page <?= e((string) $pagination['current']) ?> sur <?= e((string) $pagination['last']) ?></span>
    <?php if ($pagination['has_next']): ?>
        <a href="<?= e(url('/accueil.php?page=' . $pagination['next'] . ($categoryId ? '&categorie=' . $categoryId : ''))) ?>" class="pagination__btn">Suivant</a>
    <?php endif; ?>
</nav>

<?php require __DIR__ . '/includes/footer.php'; ?>
