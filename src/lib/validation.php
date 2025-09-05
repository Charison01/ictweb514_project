<?php
// src/lib/validation.php
function v_trim(string $s): string { return trim($s); }
function v_required(string $s, string $field, array &$errors): void {
    if ($s === '') { $errors[] = "$field is required"; }
}
function v_email(string $s, array &$errors): void {
    if (!filter_var($s, FILTER_VALIDATE_EMAIL)) { $errors[] = "Valid email required"; }
}
function esc(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
