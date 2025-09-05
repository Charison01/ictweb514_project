<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('admin');
require_once __DIR__ . '/../../../../src/lib/db.php';

$pdo = db();
$stmt = $pdo->query("SELECT c.id, c.course_code, c.name, u.name AS teacher, c.day, c.start_time, c.end_time
                     FROM courses c
                     JOIN users u ON c.teacher_id = u.id
                     ORDER BY c.course_code");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section>
  <h1 class="text-2xl font-semibold mb-4">Manage Courses</h1>
  <p><a href="?page=timetable&action=admin_course_new" class="bg-blue-600 text-white px-3 py-1 rounded">+ Add New Course</a></p>
  <table class="w-full border mt-4 bg-white rounded">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-2 py-1">Code</th>
        <th class="px-2 py-1">Name</th>
        <th class="px-2 py-1">Teacher</th>
        <th class="px-2 py-1">Schedule</th>
        <th class="px-2 py-1">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($courses as $c): ?>
        <tr class="border-t">
          <td class="px-2 py-1"><?= htmlspecialchars($c['course_code']) ?></td>
          <td class="px-2 py-1"><?= htmlspecialchars($c['name']) ?></td>
          <td class="px-2 py-1"><?= htmlspecialchars($c['teacher']) ?></td>
          <td class="px-2 py-1"><?= $c['day']." ".$c['start_time']."-".$c['end_time'] ?></td>
          <td class="px-2 py-1">
            <a href="?page=timetable&action=admin_course_edit&id=<?= (int)$c['id'] ?>" class="text-blue-600">Edit</a> |
            <a href="?page=timetable&action=admin_course_delete&id=<?= (int)$c['id'] ?>" class="text-red-600">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
