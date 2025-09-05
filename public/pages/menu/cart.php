<?php
require_once __DIR__ . '/../../../src/lib/csrf.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $id => $c) { $total += $c['qty'] * $c['price']; }

// Handle remove/clear
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { die("CSRF failed"); }
    if (isset($_POST['remove'])) {
        $rid = (int)$_POST['remove'];
        unset($_SESSION['cart'][$rid]);
    } elseif (isset($_POST['clear'])) {
        $_SESSION['cart'] = [];
    }
    header("Location: ?page=menu&action=cart"); exit;
}
?>
<section>
  <h1 class="text-2xl font-semibold mb-4">Your Cart</h1>
  <?php if (!$cart): ?>
    <p>Your cart is empty.</p>
    <a href="?page=menu&action=list" class="text-blue-600">Back to Menu</a>
  <?php else: ?>
    <table class="w-full border bg-white rounded mb-4">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-2 py-1 text-left">Item</th>
          <th class="px-2 py-1">Qty</th>
          <th class="px-2 py-1">Price</th>
          <th class="px-2 py-1">Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart as $id => $c): ?>
        <tr class="border-t">
          <td class="px-2 py-1"><?= htmlspecialchars($c['name']) ?></td>
          <td class="px-2 py-1 text-center"><?= (int)$c['qty'] ?></td>
          <td class="px-2 py-1">$<?= number_format($c['price'],2) ?></td>
          <td class="px-2 py-1">$<?= number_format($c['qty'] * $c['price'],2) ?></td>
          <td class="px-2 py-1">
            <form method="post">
              <?= csrf_field() ?>
              <button type="submit" name="remove" value="<?= (int)$id ?>" class="text-red-600">Remove</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p class="font-semibold">Total: $<?= number_format($total,2) ?></p>

    <div class="flex gap-2 mt-4">
      <form method="post">
        <?= csrf_field() ?>
        <button type="submit" name="clear" value="1" class="bg-gray-300 px-3 py-1 rounded">Clear Cart</button>
      </form>
      <a href="?page=menu&action=checkout" class="bg-green-600 text-white px-4 py-2 rounded">Proceed to Checkout</a>
    </div>
  <?php endif; ?>
</section>
