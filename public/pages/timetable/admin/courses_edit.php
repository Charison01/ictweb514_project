<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('admin');
require_once __DIR__ . '/../../../../src/lib/db.php';
require_once __DIR__ . '/../../../../src/lib/csrf.php';

$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$errors = [];

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) { echo "Course not found."; exit; }

$teachers = $pdo->query("SELECT id, name FROM users WHERE role='teacher'")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { $errors[] = "Invalid CSRF token"; }
    $code = trim($_POST['course_code'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $teacher_id = (int)($_POST['teacher_id'] ?? 0);
    $day = $_POST['day'] ?? '';
    $start = $_POST['start_time'] ?? '';
    $end = $_POST['end_time'] ?? '';

    if (!$errors && $code && $name && $teacher_id && $day && $start && $end) {
        $stmt = $pdo->prepare("UPDATE courses SET course_code=?, name=?, teacher_id=?, day=?, start_time=?, end_time=? WHERE id=?");
        $stmt->execute([$code,$name,$teacher_id,$day,$start,$end,$id]);
        header("Location: ?page=timetable&action=admin_courses");
        exit;
    } else {
        $errors[] = "All fields required.";
    }
}
?>
<section class="max-w-lg mx-auto">
  <h1 class="text-2xl font-semibold mb-4">Edit Course</h1>
  <?php if ($errors): ?><div class="bg-red-100 text-red-700 p-2"><?= implode('<br>', $errors) ?></div><?php endif; ?>
  <form method="post" class="space-y-3 bg-white p-4 border rounded">
    <?= csrf_field() ?>
    <input type="text" name="course_code" value="<?= htmlspecialchars($course['course_code']) ?>" required class="w-full border px-2 py-1">
    <input type="text" name="name" value="<?= htmlspecialchars($course['name']) ?>" required class="w-full border px-2 py-1">

    <select name="teacher_id" required class="w-full border px-2 py-1">
      <?php foreach ($teachers as $t): ?>
        <option value="<?= (int)$t['id'] ?>" <?= $t['id']==$course['teacher_id']?'selected':'' ?>>
          <?= htmlspecialchars($t['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="day" required class="w-full border px-2 py-1">
      <?php foreach (['Mon','Tue','Wed','Thu','Fri'] as $d): ?>
        <option value="<?= $d ?>" <?= $d==$course['day']?'selected':'' ?>><?= $d ?></option>
      <?php endforeach; ?>
    </select>

    <input type="time" name="start_time" value="<?= $course['start_time'] ?>" required class="w-full border px-2 py-1">
    <input type="time" name="end_time" value="<?= $course['end_time'] ?>" required class="w-full border px-2 py-1">

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
  </form>
</section>

