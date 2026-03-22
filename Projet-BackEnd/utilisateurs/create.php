<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
requireRole('administrateur');

// Creation d'un nouveau compte par l'administrateur.
$pageTitle = 'Nouvel utilisateur';
$errors = [];

if (isPost()) {
    // Lecture de toutes les donnees saisies dans le formulaire.
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
        'mot_de_passe' => 'mot de passe',
        'role' => 'role',
    ]);

    if (!in_array($data['role'], ['editeur', 'administrateur'], true)) {
        $errors['role'] = 'Role invalide.';
    }

    if (!$errors) {
        // Le login doit etre unique pour garantir une authentification fiable.
        if (valueExists('utilisateurs', 'login', $data['login'])) {
            $errors['login'] = 'Ce login est deja utilise.';
        }
    }

    if (!$errors) {
        // Le mot de passe est hache avant stockage en base.
        $stmt = getPDO()->prepare(
            'INSERT INTO utilisateurs (prenom, nom, login, mot_de_passe, role)
             VALUES (:prenom, :nom, :login, :mot_de_passe, :role)'
        );
        $stmt->execute([
            'prenom' => $data['prenom'],
            'nom' => $data['nom'],
            'login' => $data['login'],
            'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            'role' => $data['role'],
        ]);

        setFlash('success', 'Utilisateur ajoute avec succes.');
        redirect(url('/utilisateurs/index.php'));
    }
}

require __DIR__ . '/../entete.php';
?>
<section>
    <h2>Ajouter un utilisateur</h2>
    <?php require __DIR__ . '/form.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
