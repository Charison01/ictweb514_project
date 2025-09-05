<?php
require_once __DIR__ . '/../../../src/lib/db.php';
require_once __DIR__ . '/../../../src/lib/csrf.php';
require_once __DIR__ . '/../../../src/lib/validation.php';
$pdo = db();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(404); echo "Not found"; exit; }

$stmt = $pdo->prepare("SELECT id, name, email FROM address_book WHERE id=?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$item) { http_response_code(404); echo "Not found"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { die("Security token invalid"); }
    $del = $pdo->prepare("DELETE FROM address_book WHERE id=?");
    $del->execute([$id]);
    header("Location: ?page=addressbook&action=list"); exit;
}
?>
<section class="space-y-4">
  <h1 class="text-2xl font-semibold">Delete Contact</h1>
  <div class="bg-white border rounded p-4">
    <p>Are you sure you want to delete <strong><?= esc($item['name']) ?></strong> (<?= esc($item['email']) ?>)?</p>
    <form method="post" class="mt-4 flex gap-2">
      <?= csrf_field() ?>
      <button class="bg-red-600 text-white px-4 py-2 rounded" type="submit">Yes, delete</button>
      <a class="px-3 py-2 bg-gray-200 rounded" href="?page=addressbook&action=list">Cancel</a>
    </form>
  </div>
</section>
