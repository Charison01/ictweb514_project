<?php
require_once __DIR__ . '/../../../src/lib/db.php';
require_once __DIR__ . '/../../../src/lib/validation.php';
$pdo = db();

$q = isset($_GET['q']) ? v_trim($_GET['q']) : '';
$sql = "SELECT id, name, email, phone, address FROM address_book";
$params = [];
if ($q !== '') {
  $sql .= " WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR address LIKE ?";
  $like = "%$q%"; $params = [$like,$like,$like,$like];
}
$sql .= " ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="space-y-4">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-semibold">Address Book</h1>
    <a href="?page=addressbook&action=create" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Contact</a>
  </div>

  <form method="get" class="flex gap-2">
    <input type="hidden" name="page" value="addressbook">
    <input type="hidden" name="action" value="list">
    <input type="text" name="q" value="<?= esc($q) ?>" placeholder="Search name/email/phone/address" class="border px-2 py-1 rounded w-full">
    <button class="px-3 py-1 bg-gray-200 rounded">Search</button>
  </form>

  <div class="overflow-x-auto">
    <table class="mt-2 w-full border border-gray-300 bg-white rounded">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-2 py-1 text-left">Name</th>
          <th class="px-2 py-1 text-left">Email</th>
          <th class="px-2 py-1 text-left">Phone</th>
          <th class="px-2 py-1 text-left">Address</th>
          <th class="px-2 py-1">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($contacts as $c): ?>
        <tr class="border-t">
          <td class="px-2 py-1"><?= esc($c['name']) ?></td>
          <td class="px-2 py-1"><?= esc($c['email']) ?></td>
          <td class="px-2 py-1"><?= esc($c['phone']) ?></td>
          <td class="px-2 py-1"><?= esc($c['address']) ?></td>
          <td class="px-2 py-1 text-center">
            <a class="text-blue-600" href="?page=addressbook&action=edit&id=<?= (int)$c['id'] ?>">Edit</a> |
            <a class="text-red-600" href="?page=addressbook&action=delete&id=<?= (int)$c['id'] ?>">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
