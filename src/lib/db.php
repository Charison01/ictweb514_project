<?php
require_once __DIR__ . '/../../config.php'; // sets $pdo
function db(): PDO {
    // Return the global PDO created in config.php
    global $pdo;
    return $pdo;
}
