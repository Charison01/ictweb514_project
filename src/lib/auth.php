<?php
// src/lib/auth.php
require_once __DIR__ . '/db.php';

function auth_login(string $email, string $password): bool {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT id, name, email, role, password_hash FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true); // prevent fixation
        $_SESSION['user'] = [
            'id'    => (int)$user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];
        return true;
    }
    return false;
}

function auth_logout(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_role(string $role): bool {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === $role;
}

function require_login(): void {
    if (!current_user()) {
        header("Location: ?page=timetable&action=login");
        exit;
    }
}

function require_role(string $role): void {
    require_login();
    if ($_SESSION['user']['role'] !== $role) {
        http_response_code(403);
        echo "<div class='p-6 bg-red-100 text-red-700'>Access denied.</div>";
        exit;
    }
}
