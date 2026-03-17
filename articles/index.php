<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('editeur', 'administrateur');

// Espace de gestion des articles reserve aux editeurs et administrateurs.
$pdo = getPDO();
$pageTitle = 'Gestion des articles';

// Charge la liste complete des articles avec leur categorie et leur auteur.
$stmt = $pdo->query(
    'SELECT a.id, a.titre, a.date_publication, c.nom AS categorie, u.prenom, u.nom
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
    <!-- Lien vers le formulaire de creation. -->
    <p><a href="<?= e(url('/articles/create.php')) ?>">Nouvel article</a></p>
</section>
<section>
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
        <!-- Chaque ligne affiche les actions de consultation, modification et suppression. -->
        <?php foreach ($articles as $article): ?>
            <tr>
                <td><?= e($article['titre']) ?></td>
                <td><?= e($article['categorie']) ?></td>
                <td><?= e($article['prenom'] . ' ' . $article['nom']) ?></td>
                <td><?= e($article['date_publication']) ?></td>
                <td>
                    <a href="<?= e(url('/articles/voir.php?id=' . (string) $article['id'])) ?>">Voir</a>
                    <a href="<?= e(url('/articles/edit.php?id=' . (string) $article['id'])) ?>">Modifier</a>
                    <!-- La suppression passe par POST pour eviter une action sensible via simple lien GET. -->
                    <form method="post" action="<?= e(url('/articles/delete.php')) ?>" style="display:inline;">
                        <input type="hidden" name="id" value="<?= e((string) $article['id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                        <button type="submit" onclick="return confirm('Supprimer cet article ?');">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
