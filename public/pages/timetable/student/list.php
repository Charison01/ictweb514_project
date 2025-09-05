<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('student');
require_once __DIR__ . '/../../../../src/lib/db.php';

$pdo = db();
$user = current_user();
$studentId = (int)$user['id'];

$sql = "
  SELECT 
    e.id AS enrolment_id,
    e.course_code,
    e.day, e.start_time, e.end_time,
    c.name AS course_name
  FROM enrolments e
  LEFT JOIN courses c ON c.course_code = e.course_code
  WHERE e.student_id = ?
  ORDER BY FIELD(e.day,'Mon','Tue','Wed','Thu','Fri'), e.start_time
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$studentId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="space-y-4">
  <h1 class="text-2xl font-semibold">My Enrolments</h1>

  <div>
    <a href="?page=timetable&action=student_enrol" class="bg-blue-600 text-white px-3 py-2 rounded">Browse Courses</a>
  </div>

  <?php if (!$rows): ?>
    <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mt-3">
      You’re not enrolled in any courses yet. Click <strong>Browse Courses</strong> to add one.
    </div>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full border bg-white rounded mt-3">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-2 py-1 text-left">Code</th>
            <th class="px-2 py-1 text-left">Name</th>
            <th class="px-2 py-1">Day</th>
            <th class="px-2 py-1">Time</th>
            <th class="px-2 py-1">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr class="border-t">
              <td class="px-2 py-1"><?= htmlspecialchars($r['course_code']) ?></td>
              <td class="px-2 py-1"><?= htmlspecialchars($r['course_name'] ?? '(deleted course)') ?></td>
              <td class="px-2 py-1 text-center"><?= htmlspecialchars($r['day']) ?></td>
              <td class="px-2 py-1 text-center"><?= substr($r['start_time'],0,5) ?>–<?= substr($r['end_time'],0,5) ?></td>
              <td class="px-2 py-1 text-center">
                <a class="text-red-600" href="?page=timetable&action=student_unenrol&id=<?= (int)$r['enrolment_id'] ?>">Unenrol</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>
