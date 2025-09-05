<?php
// templates/layout.php
// Expects: $title (optional), $currentPage (optional), $content (required)
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/nav.php';
?>
<main class="mx-auto max-w-6xl px-4 py-8">
  <?= $content ?? '' ?>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
