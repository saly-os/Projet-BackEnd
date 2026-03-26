<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('editeur', 'administrateur');

$pdo = getPDO();
$pageTitle = 'Gestion des articles';
$user = currentUser();
$isAdmin = hasRole('administrateur');

$stmt = $pdo->query(
    'SELECT a.id, a.titre, a.date_publication, a.auteur_id, c.nom AS categorie, u.prenom, u.nom
     FROM articles a
     INNER JOIN categories c ON c.id = a.categorie_id
     INNER JOIN utilisateurs u ON u.id = a.auteur_id
     ORDER BY a.date_publication DESC'
);
$articles = $stmt->fetchAll();

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Articles</h2>
    <p class="page-actions"><a href="<?= e(url('/articles/create.php')) ?>" class="btn btn--primary">Nouvel article</a></p>
</section>
<section class="content-card table-wrap">
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Categorie</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($articles as $article): ?>
            <?php $canManage = $isAdmin || (int) $article['auteur_id'] === (int) $user['id']; ?>
            <tr>
                <td><?= e($article['titre']) ?></td>
                <td><?= e($article['categorie']) ?></td>
                <td><?= e($article['prenom'] . ' ' . $article['nom']) ?></td>
                <td><?= e($article['date_publication']) ?></td>
                <td class="table-actions">
                    <a href="<?= e(url('/articles/voir.php?id=' . (string) $article['id'])) ?>" class="action-link">Voir</a>
                    <?php if ($canManage): ?>
                        <a href="<?= e(url('/articles/edit.php?id=' . (string) $article['id'])) ?>" class="action-link">Modifier</a>
                        <form method="post" action="<?= e(url('/articles/delete.php')) ?>" style="display:inline;">
                            <input type="hidden" name="id" value="<?= e((string) $article['id']) ?>">
                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet article ?');">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
