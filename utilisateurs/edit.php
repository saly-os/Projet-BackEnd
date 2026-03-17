<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('administrateur');

// Modification d'un compte utilisateur existant.
$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Refuse les identifiants invalides.
if (!$id) {
    setFlash('error', 'Utilisateur introuvable.');
    redirect(url('/utilisateurs/index.php'));
}

$stmt = $pdo->prepare('SELECT id, prenom, nom, login, role FROM utilisateurs WHERE id = :id');
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();

// L'utilisateur a modifier doit exister.
if (!$user) {
    setFlash('error', 'Utilisateur introuvable.');
    redirect(url('/utilisateurs/index.php'));
}

$pageTitle = 'Modifier un utilisateur';
$errors = [];
$isEdit = true;

if (isPost()) {
    // On relit les nouvelles donnees depuis le formulaire.
    $data = [
        'prenom' => trim((string) ($_POST['prenom'] ?? '')),
        'nom' => trim((string) ($_POST['nom'] ?? '')),
        'login' => trim((string) ($_POST['login'] ?? '')),
        'mot_de_passe' => (string) ($_POST['mot_de_passe'] ?? ''),
        'role' => trim((string) ($_POST['role'] ?? '')),
    ];

    $errors = validateRequired($data, [
        'prenom' => 'prenom',
        'nom' => 'nom',
        'login' => 'login',
        'role' => 'role',
    ]);

    if (!in_array($data['role'], ['editeur', 'administrateur'], true)) {
        $errors['role'] = 'Role invalide.';
    }

    if (!$errors) {
        // Le login doit rester unique, hors utilisateur courant.
        if (valueExists('utilisateurs', 'login', $data['login'], $id)) {
            $errors['login'] = 'Ce login est deja utilise.';
        }
    }

    if (!$errors) {
        if ($data['mot_de_passe'] !== '') {
            // Si un mot de passe est saisi, on le remplace par un nouveau hash.
            $update = $pdo->prepare(
                'UPDATE utilisateurs
                 SET prenom = :prenom, nom = :nom, login = :login, mot_de_passe = :mot_de_passe, role = :role
                 WHERE id = :id'
            );
            $update->execute([
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'login' => $data['login'],
                'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
                'role' => $data['role'],
                'id' => $id,
            ]);
        } else {
            // Sinon, on met a jour uniquement les informations de profil.
            $update = $pdo->prepare(
                'UPDATE utilisateurs
                 SET prenom = :prenom, nom = :nom, login = :login, role = :role
                 WHERE id = :id'
            );
            $update->execute([
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'login' => $data['login'],
                'role' => $data['role'],
                'id' => $id,
            ]);
        }

        setFlash('success', 'Utilisateur modifie avec succes.');
        redirect(url('/utilisateurs/index.php'));
    }

    // Les valeurs postées sont reinjectees dans le formulaire en cas d'erreur.
    $user = array_merge($user, $data);
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Modifier un utilisateur</h2>
    <?php require __DIR__ . '/form.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
