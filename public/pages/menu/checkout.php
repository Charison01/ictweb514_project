<?php
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $c) { $total += $c['qty'] * $c['price']; }
?>
<section>
  <h1 class="text-2xl font-semibold mb-4">Checkout Summary</h1>
  <?php if (!$cart): ?>
    <p>Your cart is empty. <a href="?page=menu&action=list" class="text-blue-600">Back to Menu</a></p>
  <?php else: ?>
    <ul class="mb-4">
      <?php foreach ($cart as $c): ?>
        <li><?= (int)$c['qty'] ?> × <?= htmlspecialchars($c['name']) ?> @ $<?= number_format($c['price'],2) ?></li>
      <?php endforeach; ?>
    </ul>
    <p class="font-bold text-lg">Total: $<?= number_format($total,2) ?></p>
    <p class="mt-4 text-gray-600">This is a stub checkout page. No payment processing is implemented.</p>
  <?php endif; ?>
</section>
