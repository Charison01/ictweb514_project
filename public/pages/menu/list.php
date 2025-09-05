<?php
require_once __DIR__ . '/../../../src/lib/db.php';
require_once __DIR__ . '/../../../src/lib/csrf.php';
$pdo = db();

// Handle add-to-cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    if (!csrf_verify()) { die("CSRF failed"); }
    $id = (int)$_POST['item_id'];
    $qty = max(1, (int)$_POST['qty']); // min 1

    // fetch price + name from DB (server authority)
    $stmt = $pdo->prepare("SELECT id, name, price FROM menu_item WHERE id=?");
    $stmt->execute([$id]);
    if ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['cart'][$id]['name']  = $item['name'];
        $_SESSION['cart'][$id]['price'] = $item['price'];
        $_SESSION['cart'][$id]['qty']   = ($_SESSION['cart'][$id]['qty'] ?? 0) + $qty;
    }
    header("Location: ?page=menu&action=cart"); exit;
}

// fetch menu
$stmt = $pdo->query("SELECT id, name, description, price FROM menu_item ORDER BY name ASC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section>
  <h1 class="text-2xl font-semibold mb-4">Café Menu</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($items as $it): ?>
      <div class="border rounded p-4 bg-white shadow">
        <h2 class="font-bold text-lg"><?= htmlspecialchars($it['name']) ?></h2>
        <p class="text-gray-600 mb-2"><?= htmlspecialchars($it['description']) ?></p>
        <p class="font-semibold mb-2">$<?= number_format($it['price'],2) ?></p>
        <form method="post" class="flex gap-2 items-center">
          <?= csrf_field() ?>
          <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
          <input type="number" name="qty" value="1" min="1" class="w-16 border rounded px-1">
          <button class="bg-blue-600 text-white px-3 py-1 rounded">Add</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</section>
