<?php
require_once __DIR__ . '/../../../src/lib/db.php';
require_once __DIR__ . '/../../../src/lib/csrf.php';
require_once __DIR__ . '/../../../src/lib/validation.php';
$pdo = db();
$errors = [];
$name = $email = $phone = $address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { $errors[] = "Security token invalid"; }
    $name = v_trim($_POST['name'] ?? '');
    $email = v_trim($_POST['email'] ?? '');
    $phone = v_trim($_POST['phone'] ?? '');
    $address = v_trim($_POST['address'] ?? '');

    v_required($name, 'Name', $errors);
    v_email($email, $errors);

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO address_book (name, email, phone, address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $address]);
            header("Location: ?page=addressbook&action=list"); exit;
        } catch (PDOException $e) {
            // Duplicate email (UNIQUE)
            if ($e->getCode() === '23000') { $errors[] = "Email already exists"; }
            else { $errors[] = "Database error: ".$e->getMessage(); }
        }
    }
}
?>
<section class="space-y-4">
  <h1 class="text-2xl font-semibold">Add Contact</h1>
  <?php if ($errors): ?>
    <div class="bg-red-100 text-red-700 p-2 rounded"><?php foreach ($errors as $er) { echo esc($er)."<br>"; } ?></div>
  <?php endif; ?>

  <form method="post" class="space-y-3 bg-white p-4 rounded border">
    <?= csrf_field() ?>
    <input class="w-full border px-2 py-1" name="name"  placeholder="Name"  value="<?= esc($name) ?>" required>
    <input class="w-full border px-2 py-1" name="email" type="email" placeholder="Email" value="<?= esc($email) ?>" required>
    <input class="w-full border px-2 py-1" name="phone" placeholder="Phone" value="<?= esc($phone) ?>">
    <textarea class="w-full border px-2 py-1" name="address" placeholder="Address"><?= esc($address) ?></textarea>
    <div class="flex gap-2">
      <button class="bg-green-600 text-white px-4 py-2 rounded" type="submit">Save</button>
      <a href="?page=addressbook&action=list" class="px-3 py-2 bg-gray-200 rounded">Cancel</a>
    </div>
  </form>
</section>
