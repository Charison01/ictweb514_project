<?php
// public/index.php

// (Optional) Show errors in dev; hide in prod
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Determine requested page (whitelist)
$allowedPages = ['home', 'addressbook', 'menu', 'timetable'];
$page = $_GET['page'] ?? 'home';
if (!in_array($page, $allowedPages, true)) {
  $page = 'home';
}

// Title + current page for UI
$titles = [
  'home'        => 'Home · ICTWEB514',
  'addressbook' => 'Address Book · ICTWEB514',
  'menu'        => 'Café Menu · ICTWEB514',
  'timetable'   => 'Timetable · ICTWEB514',
];
$title = $titles[$page] ?? 'ICTWEB514 Project';
$currentPage = $page;

// Capture page content
ob_start();
$pageFile = __DIR__ . "/pages/{$page}.php";
if (file_exists($pageFile)) {
  include $pageFile;
} else {
  echo "<div class='p-6 bg-red-50 border border-red-200 rounded-md'>Page not found.</div>";
}
$content = ob_get_clean();

// Render inside the layout
include __DIR__ . '/../templates/layout.php';
