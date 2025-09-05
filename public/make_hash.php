<?php
header('Content-Type: text/plain');
echo "Admin:   " . password_hash('Admin@123', PASSWORD_DEFAULT) . PHP_EOL;
echo "Teacher: " . password_hash('Teacher@123', PASSWORD_DEFAULT) . PHP_EOL;
echo "Student: " . password_hash('Student@123', PASSWORD_DEFAULT) . PHP_EOL;
