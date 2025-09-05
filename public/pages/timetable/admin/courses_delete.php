<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('admin');
require_once __DIR__ . '/../../../../src/lib/db.php';
require_once __DIR__ . '/../../../../src/lib/csrf.php';

$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) { echo "Course not found."; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { die("Invalid CSRF token"); }
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id=?");
    $stmt->execute([$id]);
    header("Location: ?page=timetable&action=admin_courses");
    exit;
}
?>
<section class="max-w-lg mx-auto">
  <h1 class="text-2xl font-semibold mb-4">Delete Course</h1>
  <p>Are you sure you want to delete course <strong><?= htmlspecialchars($course['course_code'].' - '.$course['name']) ?></strong>?</p>
  <form method="post" class="mt-4">
    <?= csrf_field() ?>
    <button class="bg-red-600 text-white px-4 py-2 rounded">Yes, Delete</button>
    <a href="?page=timetable&action=admin_courses" class="ml-2 text-blue-600">Cancel</a>
  </form>
</section>
