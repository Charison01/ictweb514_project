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
