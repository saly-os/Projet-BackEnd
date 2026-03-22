<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function url(string $path): string
{
    return APP_BASE_PATH . $path;
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function old(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool
{
    return currentUser() !== null;
}

function hasRole(string ...$roles): bool
{
    $user = currentUser();

    return $user !== null && in_array($user['role'], $roles, true);
}

function refreshSessionUser(): bool
{
    $user = currentUser();

    if ($user === null) {
        return false;
    }

    $stmt = getPDO()->prepare('SELECT id, prenom, nom, login, role FROM utilisateurs WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $user['id']]);
    $freshUser = $stmt->fetch();

    if (!$freshUser) {
        unset($_SESSION['user']);

        return false;
    }

    $_SESSION['user'] = [
        'id' => (int) $freshUser['id'],
        'prenom' => $freshUser['prenom'],
        'nom' => $freshUser['nom'],
        'login' => $freshUser['login'],
        'role' => $freshUser['role'],
    ];

    return true;
}

function requireLogin(): void
{
    if (!isLoggedIn() || !refreshSessionUser()) {
        $_SESSION['flash_error'] = 'Veuillez vous connecter pour acceder a cette page.';
        redirect(url('/connexion.php'));
    }
}

function requireRole(string ...$roles): void
{
    requireLogin();

    if (!hasRole(...$roles)) {
        $_SESSION['flash_error'] = 'Acces non autorise.';
        redirect(url('/connexion.php'));
    }
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash_' . $type] = $message;
}

function getFlash(string $type): ?string
{
    $key = 'flash_' . $type;

    if (!isset($_SESSION[$key])) {
        return null;
    }

    $message = (string) $_SESSION[$key];
    unset($_SESSION[$key]);

    return $message;
}

function validateRequired(array $data, array $fields): array
{
    $errors = [];

    foreach ($fields as $field => $label) {
        if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
            $errors[$field] = sprintf('Le champ %s est obligatoire.', $label);
        }
    }

    return $errors;
}

function valueExists(string $table, string $column, string $value, ?int $ignoreId = null): bool
{
    $sql = sprintf('SELECT id FROM %s WHERE %s = :value', $table, $column);
    $params = ['value' => $value];

    if ($ignoreId !== null) {
        $sql .= ' AND id != :ignore_id';
        $params['ignore_id'] = $ignoreId;
    }

    $sql .= ' LIMIT 1';

    $stmt = getPDO()->prepare($sql);
    $stmt->execute($params);

    return (bool) $stmt->fetchColumn();
}

function categoryExists(int $categoryId): bool
{
    $stmt = getPDO()->prepare('SELECT id FROM categories WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $categoryId]);

    return (bool) $stmt->fetchColumn();
}

function csrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function requireValidPostWithCsrf(): void
{
    if (!isPost() || !verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        setFlash('error', 'Requete invalide.');
        redirect(url('/connexion.php'));
    }
}

function paginate(int $total, int $perPage, int $currentPage): array
{
    $lastPage = max(1, (int) ceil($total / $perPage));
    $currentPage = min(max(1, $currentPage), $lastPage);
    $offset = ($currentPage - 1) * $perPage;

    return [
        'current' => $currentPage,
        'last' => $lastPage,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $lastPage,
        'prev' => max(1, $currentPage - 1),
        'next' => min($lastPage, $currentPage + 1),
    ];
}
