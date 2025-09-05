<?php
require_once __DIR__ . '/../../../../src/lib/auth.php';
require_role('teacher');
require_once __DIR__ . '/../../../../src/lib/db.php';

$pdo = db();
$user = current_user();

// fetch courses where this teacher is assigned
$stmt = $pdo->prepare("SELECT course_code, name, day, start_time, end_time 
                       FROM courses 
                       WHERE teacher_id=? 
                       ORDER BY day, start_time");
$stmt->execute([$user['id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section>
  <h1 class="text-2xl font-semibold mb-4">My Teaching Schedule</h1>
  <?php if (!$courses): ?>
    <p>You are not assigned to any courses yet.</p>
  <?php else: ?>
    <table class="w-full border bg-white rounded">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-2 py-1">Code</th>
          <th class="px-2 py-1">Name</th>
          <th class="px-2 py-1">Day</th>
          <th class="px-2 py-1">Time</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($courses as $c): ?>
          <tr class="border-t">
            <td class="px-2 py-1"><?= htmlspecialchars($c['course_code']) ?></td>
            <td class="px-2 py-1"><?= htmlspecialchars($c['name']) ?></td>
            <td class="px-2 py-1"><?= htmlspecialchars($c['day']) ?></td>
            <td class="px-2 py-1"><?= substr($c['start_time'],0,5) ?> - <?= substr($c['end_time'],0,5) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>
