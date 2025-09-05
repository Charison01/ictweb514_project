<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('admin');
require_once __DIR__ . '/../../../../src/lib/db.php';
require_once __DIR__ . '/../../../../src/lib/csrf.php';

$pdo = db();
$errors = [];

// fetch teachers
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
        $stmt = $pdo->prepare("INSERT INTO courses (course_code, name, teacher_id, day, start_time, end_time) 
                               VALUES (?,?,?,?,?,?)");
        try {
            $stmt->execute([$code,$name,$teacher_id,$day,$start,$end]);
            header("Location: ?page=timetable&action=admin_courses");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    } else {
        $errors[] = "All fields required.";
    }
}
?>
<section class="max-w-lg mx-auto">
  <h1 class="text-2xl font-semibold mb-4">Add New Course</h1>
  <?php if ($errors): ?><div class="bg-red-100 text-red-700 p-2"><?= implode('<br>', $errors) ?></div><?php endif; ?>
  <form method="post" class="space-y-3 bg-white p-4 border rounded">
    <?= csrf_field() ?>
    <input type="text" name="course_code" placeholder="Course Code" required class="w-full border px-2 py-1">
    <input type="text" name="name" placeholder="Course Name" required class="w-full border px-2 py-1">

    <select name="teacher_id" required class="w-full border px-2 py-1">
      <option value="">-- Assign Teacher --</option>
      <?php foreach ($teachers as $t): ?>
        <option value="<?= (int)$t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="day" required class="w-full border px-2 py-1">
      <option value="">-- Day --</option>
      <?php foreach (['Mon','Tue','Wed','Thu','Fri'] as $d): ?>
        <option value="<?= $d ?>"><?= $d ?></option>
      <?php endforeach; ?>
    </select>

    <input type="time" name="start_time" required class="w-full border px-2 py-1">
    <input type="time" name="end_time" required class="w-full border px-2 py-1">

    <button class="bg-green-600 text-white px-4 py-2 rounded">Create</button>
  </form>
</section>
