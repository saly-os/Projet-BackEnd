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
    <form class="filter-form" id="filterForm">
        <div class="filter-form__group">
            <label for="categorie">Filtrer par categorie</label>
            <select id="categorie" name="categorie">
                <option value="">Toutes les categories</option>
                <option value="1">Technologie</option>
                <option value="2">Sport</option>
                <option value="3">Politique</option>
                <option value="4">Culture</option>
            </select>
        </div>

        <div class="filter-form__actions">
            <button type="submit" class="btn btn--primary">Filtrer</button>
            <a href="#" class="btn btn--secondary">Reinitialiser</a>
        </div>
    </form>
</section>

<section class="articles">
    <div class="articles__header">
        <h3>Articles recents</h3>
        <p>12 articles trouves</p>
    </div>

    <div class="articles__grid">
        <article class="article-card">
            <div class="article-card__top">
                <span class="badge">Technologie</span>
                <span class="article-card__date">17/03/2026</span>
            </div>

            <h3 class="article-card__title">
                <a href="#">Titre de l'article</a>
            </h3>

            <p class="article-card__author">Par Jean Dupont</p>

            <p class="article-card__excerpt">
                Ceci est une courte description de l'article pour donner envie au visiteur de lire la suite.
            </p>

            <a href="#" class="article-card__link">Lire l'article</a>
        </article>

        <article class="article-card">
            <div class="article-card__top">
                <span class="badge">Culture</span>
                <span class="article-card__date">16/03/2026</span>
            </div>

            <h3 class="article-card__title">
                <a href="#">Deuxieme article</a>
            </h3>

            <p class="article-card__author">Par Marie Ali</p>

            <p class="article-card__excerpt">
                Une autre description courte qui presente rapidement le contenu de l'article.
            </p>

            <a href="#" class="article-card__link">Lire l'article</a>
        </article>
    </div>
</section>

<nav class="pagination" aria-label="Pagination">
    <a href="#" class="pagination__btn">Precedent</a>
    <span class="pagination__info">Page 1 sur 3</span>
    <a href="#" class="pagination__btn">Suivant</a>
</nav>


<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/config/database.php';

// Liste publique des articles avec filtre par categorie et pagination simple.
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
