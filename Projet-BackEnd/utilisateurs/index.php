<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('administrateur');

// Espace d'administration des comptes utilisateurs.
$pdo = getPDO();
$pageTitle = 'Utilisateurs';

// Charge tous les comptes a des fins de gestion par l'administrateur.
$users = $pdo->query('SELECT id, prenom, nom, login, role FROM utilisateurs ORDER BY role, nom, prenom')->fetchAll();

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Utilisateurs</h2>
    <!-- Seul l'administrateur voit et utilise cet ecran. -->
    <p><a href="<?= e(url('/utilisateurs/create.php')) ?>">Nouvel utilisateur</a></p>
</section>
<section>
    <table>
        <thead>
            <tr>
                <th>Prenom</th>
                <th>Nom</th>
                <th>Login</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <!-- Chaque utilisateur peut etre modifie et, sauf soi-meme, supprime. -->
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= e($user['prenom']) ?></td>
                <td><?= e($user['nom']) ?></td>
                <td><?= e($user['login']) ?></td>
                <td><?= e($user['role']) ?></td>
                <td>
                    <a href="<?= e(url('/utilisateurs/edit.php?id=' . (string) $user['id'])) ?>">Modifier</a>
                    <?php if ((int) $user['id'] !== (int) currentUser()['id']): ?>
                        <!-- On interdit la suppression de sa propre session d'administration. -->
                        <form method="post" action="<?= e(url('/utilisateurs/delete.php')) ?>" style="display:inline;">
                            <input type="hidden" name="id" value="<?= e((string) $user['id']) ?>">
                            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                            <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
