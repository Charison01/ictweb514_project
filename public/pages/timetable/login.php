<?php
require_once __DIR__ . '/../../../src/lib/auth.php';
require_once __DIR__ . '/../../../src/lib/csrf.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { $errors[] = "Invalid CSRF token"; }
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$errors && !auth_login($email, $password)) {
        $errors[] = "Invalid email or password";
    }
    if (!$errors) {
        header("Location: ?page=timetable&action=dashboard");
        exit;
    }
}
?>
<section class="max-w-md mx-auto space-y-4">
  <h1 class="text-2xl font-semibold">Login</h1>
  <?php if ($errors): ?>
    <div class="bg-red-100 text-red-700 p-2 rounded">
      <?php foreach($errors as $e) echo htmlspecialchars($e)."<br>"; ?>
    </div>
  <?php endif; ?>
  <form method="post" class="space-y-3 bg-white p-4 rounded border">
    <?= csrf_field() ?>
    <input type="email" name="email" placeholder="Email" required class="w-full border px-2 py-1">
    <input type="password" name="password" placeholder="Password" required class="w-full border px-2 py-1">
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
  </form>
</section>
