<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

// Liste publique des categories et compteur d'articles.
$pdo = getPDO();
$pageTitle = 'Categories';

// La requete compte le nombre d'articles rattaches a chaque categorie.
$categories = $pdo->query(
    'SELECT c.id, c.nom, COUNT(a.id) AS total_articles
     FROM categories c
     LEFT JOIN articles a ON a.categorie_id = c.id
     GROUP BY c.id, c.nom
     ORDER BY c.nom'
)->fetchAll();

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Categories</h2>
    <?php if (hasRole('editeur', 'administrateur')): ?>
        <!-- Les visiteurs consultent seulement, les editeurs peuvent gerer. -->
        <p><a href="<?= e(url('/categories/create.php')) ?>">Nouvelle categorie</a></p>
    <?php endif; ?>
</section>

<section>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Articles</th>
                <th>Consultation</th>
                <?php if (hasRole('editeur', 'administrateur')): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <!-- Chaque categorie peut etre consultee et, selon le role, modifiee/supprimee. -->
        <?php foreach ($categories as $categorie): ?>
            <tr>
                <td><?= e($categorie['nom']) ?></td>
                <td><?= e((string) $categorie['total_articles']) ?></td>
                <td><a href="<?= e(url('/accueil.php?categorie=' . (string) $categorie['id'])) ?>">Voir les articles</a></td>
                <?php if (hasRole('editeur', 'administrateur')): ?>
                    <td>
                        <a href="<?= e(url('/categories/edit.php?id=' . (string) $categorie['id'])) ?>">Modifier</a>
                        <!-- La suppression est protegee par POST + CSRF. -->
                        <form method="post" action="<?= e(url('/categories/delete.php')) ?>" style="display:inline;">
                            <input type="hidden" name="id" value="<?= e((string) $categorie['id']) ?>">
                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                            <button type="submit" onclick="return confirm('Supprimer cette categorie ?');">Supprimer</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
