<?php
require_once __DIR__ . '/../../../src/lib/auth.php';
require_login();

$user = current_user();
$role = $user['role'];

echo "<h1 class='text-2xl font-semibold mb-4'>Welcome, ".htmlspecialchars($user['name'])."</h1>";

if ($role === 'admin') {
    echo "<p><a href='?page=timetable&action=admin_courses' class='text-blue-600'>Manage Courses</a></p>";
} elseif ($role === 'teacher') {
    echo "<p><a href='?page=timetable&action=teacher_schedule' class='text-blue-600'>View My Schedule</a></p>";
} elseif ($role === 'student') {
    echo "<p><a href='?page=timetable&action=student_list' class='text-blue-600'>View My Enrolments</a></p>";
}
