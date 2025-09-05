<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('student');
require_once __DIR__ . '/../../../../src/lib/db.php';
require_once __DIR__ . '/../../../../src/lib/csrf.php';

$pdo = db();
$user = current_user();
$studentId = (int)$user['id'];
$errors = [];
$notice = "";

// Handle enrol POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_code'])) {
    if (!csrf_verify()) { $errors[] = "Invalid CSRF token."; }
    $courseCode = trim($_POST['course_code'] ?? '');

    if (!$errors) {
        // Confirm course exists
        $cstmt = $pdo->prepare("SELECT course_code, day, start_time, end_time, name FROM courses WHERE course_code=?");
        $cstmt->execute([$courseCode]);
        $course = $cstmt->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            $errors[] = "Course not found.";
        } else {
            // Check not already enrolled
            $x = $pdo->prepare("SELECT 1 FROM enrolments WHERE student_id=? AND course_code=?");
            $x->execute([$studentId, $courseCode]);
            if ($x->fetch()) {
                $errors[] = "You are already enrolled in {$course['course_code']}.";
            } else {
                // Insert enrolment by copying schedule from courses
                $ins = $pdo->prepare("
                  INSERT INTO enrolments (student_id, course_code, day, start_time, end_time)
                  VALUES (?, ?, ?, ?, ?)
                ");
                $ins->execute([
                  $studentId,
                  $course['course_code'],
                  $course['day'],
                  $course['start_time'],
                  $course['end_time']
                ]);
                header("Location: ?page=timetable&action=student_list");
                exit;
            }
        }
    }
}

// Fetch courses NOT already enrolled by this student
$sql = "
  SELECT c.course_code, c.name, c.day, c.start_time, c.end_time, u.name AS teacher
  FROM courses c
  JOIN users u ON u.id = c.teacher_id
  WHERE c.course_code NOT IN (SELECT e.course_code FROM enrolments e WHERE e.student_id = ?)
  ORDER BY FIELD(c.day,'Mon','Tue','Wed','Thu','Fri'), c.start_time
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$studentId]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="space-y-4">
  <h1 class="text-2xl font-semibold">Enrol in a Course</h1>

  <?php if ($errors): ?>
    <div class="bg-red-100 text-red-700 p-2 rounded"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
  <?php elseif ($notice): ?>
    <div class="bg-blue-100 text-blue-700 p-2 rounded"><?= htmlspecialchars($notice) ?></div>
  <?php endif; ?>

  <?php if (!$courses): ?>
    <div class="bg-green-50 border border-green-200 rounded p-4">
      You’ve already enrolled in all available courses.
    </div>
    <a href="?page=timetable&action=student_list" class="text-blue-600 underline">Back to My Enrolments</a>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full border bg-white rounded">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-2 py-1 text-left">Code</th>
            <th class="px-2 py-1 text-left">Name</th>
            <th class="px-2 py-1 text-left">Teacher</th>
            <th class="px-2 py-1">Day</th>
            <th class="px-2 py-1">Time</th>
            <th class="px-2 py-1">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($courses as $c): ?>
            <tr class="border-t">
              <td class="px-2 py-1"><?= htmlspecialchars($c['course_code']) ?></td>
              <td class="px-2 py-1"><?= htmlspecialchars($c['name']) ?></td>
              <td class="px-2 py-1"><?= htmlspecialchars($c['teacher']) ?></td>
              <td class="px-2 py-1 text-center"><?= htmlspecialchars($c['day']) ?></td>
              <td class="px-2 py-1 text-center"><?= substr($c['start_time'],0,5) ?>–<?= substr($c['end_time'],0,5) ?></td>
              <td class="px-2 py-1 text-center">
                <form method="post" class="inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="course_code" value="<?= htmlspecialchars($c['course_code']) ?>">
                  <button class="bg-green-600 text-white px-3 py-1 rounded">Enrol</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      <a href="?page=timetable&action=student_list" class="text-blue-600 underline">Back to My Enrolments</a>
    </div>
  <?php endif; ?>
</section>
