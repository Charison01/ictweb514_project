<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('student');
require_once __DIR__ . '/../../../../src/lib/db.php';
require_once __DIR__ . '/../../../../src/lib/csrf.php';

$pdo = db();
$user = current_user();
$studentId = (int)$user['id'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Load enrolment and ensure it belongs to this student
$stmt = $pdo->prepare("
  SELECT e.id, e.course_code, e.day, e.start_time, e.end_time, c.name AS course_name
  FROM enrolments e
  LEFT JOIN courses c ON c.course_code = e.course_code
  WHERE e.id=? AND e.student_id=?
");
$stmt->execute([$id, $studentId]);
$e = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$e) {
    http_response_code(404);
    echo "<div class='p-4 bg-red-50 border border-red-200 rounded'>Enrolment not found.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { die("Invalid CSRF token"); }
    $del = $pdo->prepare("DELETE FROM enrolments WHERE id=? AND student_id=?");
    $del->execute([$id, $studentId]);
    header("Location: ?page=timetable&action=student_list");
    exit;
}
?>
<section class="space-y-4">
  <h1 class="text-2xl font-semibold">Unenrol</h1>
  <div class="bg-white border rounded p-4">
    <p>Are you sure you want to unenrol from
      <strong><?= htmlspecialchars($e['course_code']) ?> — <?= htmlspecialchars($e['course_name'] ?? '(deleted course)') ?></strong>
      on <strong><?= htmlspecialchars($e['day']) ?></strong>
      at <strong><?= substr($e['start_time'],0,5) ?>–<?= substr($e['end_time'],0,5) ?></strong>?</p>

    <form method="post" class="mt-4 flex gap-2">
      <?= csrf_field() ?>
      <button class="bg-red-600 text-white px-4 py-2 rounded" type="submit">Yes, Unenrol</button>
      <a href="?page=timetable&action=student_list" class="px-3 py-2 bg-gray-200 rounded">Cancel</a>
    </form>
  </div>
</section>
