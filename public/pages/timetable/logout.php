<?php
require_once __DIR__ . '/../../../src/lib/auth.php';
auth_logout();
header("Location: ?page=timetable&action=login");
exit;
