<?php
require_once __DIR__ . '/../src/lib/db.php';
$pdo = db();
$stmt = $pdo->query("SELECT id, name, email FROM address_book ORDER BY id LIMIT 10");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>DB Test</title></head>
<body>
<h1>DB Test: Address Book</h1>
<pre><?php print_r($rows); ?></pre>
</body>
</html>
