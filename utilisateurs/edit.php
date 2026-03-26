<?php

declare(strict_types=1);

require_once __DIR__ . '/../init.php';
requireRole('administrateur');

$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('error', 'Utilisateur introuvable.');
    redirect(url('/utilisateurs/index.php'));
}

$stmt = $pdo->prepare('SELECT id, prenom, nom, login, role FROM utilisateurs WHERE id = :id');
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();

if (!$user) {
    setFlash('error', 'Utilisateur introuvable.');
    redirect(url('/utilisateurs/index.php'));
}

$pageTitle = 'Modifier un utilisateur';
$errors = [];
$isEdit = true;

if (isPost()) {
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
        if (valueExists('utilisateurs', 'login', $data['login'], $id)) {
            $errors['login'] = 'Ce login est deja utilise.';
        }
    }

    if (!$errors) {
        if ($data['mot_de_passe'] !== '') {
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

    $user = array_merge($user, $data);
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Modifier un utilisateur</h2>
    <?php require __DIR__ . '/form.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>

<form method="post" data-validate="true" novalidate>
    <div class="form-group">
        <label for="prenom">Prenom</label>
        <input
            type="text"
            id="prenom"
            name="prenom"
            data-required="true"
            data-label="prenom"
            data-error="prenomError"
            placeholder="Modifiez le prenom"
        >
        <p class="form-error" id="prenomError"></p>
    </div>

    <div class="form-group">
        <label for="nom">Nom</label>
        <input
            type="text"
            id="nom"
            name="nom"
            data-required="true"
            data-label="nom"
            data-error="nomError"
            placeholder="Modifiez le nom"
        >
        <p class="form-error" id="nomError"></p>
    </div>

    <div class="form-group">
        <label for="login">Login</label>
        <input
            type="text"
            id="login"
            name="login"
            data-required="true"
            data-label="login"
            data-error="loginError"
            placeholder="Modifiez le login"
        >
        <p class="form-error" id="loginError"></p>
    </div>

    <div class="form-group">
        <label for="mot_de_passe">Mot de passe</label>
        <input
            type="password"
            id="mot_de_passe"
            name="mot_de_passe"
            data-required="true"
            data-label="mot de passe"
            data-error="passwordError"
            placeholder="Entrez le nouveau mot de passe"
        >
        <p class="form-error" id="passwordError"></p>
    </div>

    <div class="form-group">
        <label for="role">Role</label>
        <select
            id="role"
            name="role"
            data-required="true"
            data-label="role"
            data-error="roleError"
        >
            <option value="">Choisir un role</option>
            <option value="editeur">Editeur</option>
            <option value="administrateur">Administrateur</option>
        </select>
        <p class="form-error" id="roleError"></p>
    </div>

    <button type="submit" class="btn btn--primary">Modifier l'utilisateur</button>
</form>
