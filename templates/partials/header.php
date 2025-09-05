<!-- templates/partials/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= isset($title) ? htmlspecialchars($title) : 'ICTWEB514 Project' ?></title>

  <!-- Tailwind CSS via CDN (simple for this unit) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Basic meta -->
  <meta name="description" content="ICTWEB514 - Create Dynamic Web Pages" />
</head>
<body class="bg-gray-100 text-gray-900">
