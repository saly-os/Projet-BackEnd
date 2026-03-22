<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('administrateur');

$pdo = getPDO();
$pageTitle = 'Utilisateurs';

$users = $pdo->query('SELECT id, prenom, nom, login, role FROM utilisateurs ORDER BY role, nom, prenom')->fetchAll();

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Utilisateurs</h2>
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
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= e($user['prenom']) ?></td>
                <td><?= e($user['nom']) ?></td>
                <td><?= e($user['login']) ?></td>
                <td><?= e($user['role']) ?></td>
                <td>
                    <a href="<?= e(url('/utilisateurs/edit.php?id=' . (string) $user['id'])) ?>">Modifier</a>
                    <?php if ((int) $user['id'] !== (int) currentUser()['id']): ?>
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
