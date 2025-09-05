<?php
// public/index.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// whitelist
$allowedPages = ['home', 'addressbook', 'menu', 'timetable'];
$page = $_GET['page'] ?? 'home';
if (!in_array($page, $allowedPages, true)) {
  $page = 'home';
}


$action = $_GET['action'] ?? 'list';

if ($page === 'addressbook') {
  $allowedActions = ['list','create','edit','delete'];
  if (!in_array($action, $allowedActions, true)) { $action = 'list'; }
  $pageFile = __DIR__ . "/pages/addressbook/{$action}.php";

} elseif ($page === 'menu') {
  $allowedActions = ['list','cart','checkout'];
  if (!in_array($action, $allowedActions, true)) { $action = 'list'; }
  $pageFile = __DIR__ . "/pages/menu/{$action}.php";

} elseif ($page === 'timetable') {
  $action = $_GET['action'] ?? 'dashboard';
  $allowed = [
    'login','logout','dashboard',
    'admin_courses','admin_course_new','admin_course_edit','admin_course_delete',
    'teacher_schedule',
    'student_list','student_enrol','student_unenrol'
  ];
  if (!in_array($action, $allowed, true)) { $action = 'dashboard'; }

  $map = [
    'login' => 'timetable/login.php',
    'logout' => 'timetable/logout.php',
    'dashboard' => 'timetable/dashboard.php',

    'admin_courses' => 'timetable/admin/courses_list.php',
    'admin_course_new' => 'timetable/admin/courses_create.php',
    'admin_course_edit' => 'timetable/admin/courses_edit.php',
    'admin_course_delete' => 'timetable/admin/courses_delete.php',

    'teacher_schedule' => 'timetable/teacher/schedule.php',

    'student_list' => 'timetable/student/list.php',
    'student_enrol' => 'timetable/student/enrol.php',
    'student_unenrol' => 'timetable/student/unenrol.php',
  ];

  $pageFile = __DIR__ . '/pages/' . $map[$action];

} else {
  $pageFile = __DIR__ . "/pages/{$page}.php";
}


$titles = [
  'home'        => 'Home · ICTWEB514',
  'addressbook' => 'Address Book · ICTWEB514',
  'menu'        => 'Café Menu · ICTWEB514',
  'timetable'   => 'Timetable · ICTWEB514',
];
$title = $titles[$page] ?? 'ICTWEB514 Project';
$currentPage = $page;

// capture page-specific content
ob_start();
if (file_exists($pageFile)) {
  include $pageFile;
} else {
  echo "<div class='p-6 bg-red-50 border border-red-200 rounded-md'>Page not found.</div>";
}
$content = ob_get_clean();

// render
include __DIR__ . '/../templates/layout.php';
