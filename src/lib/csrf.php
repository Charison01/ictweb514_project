<?php
// src/lib/csrf.php
function csrf_token(): string {
    if (!isset($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="'.htmlspecialchars(csrf_token()).'">';
}
function csrf_verify(): bool {
    $ok = isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
    if (!$ok) { http_response_code(403); }
    return $ok;
}
