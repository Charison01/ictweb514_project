<!-- templates/partials/nav.php -->
<?php
  $currentPage = $currentPage ?? 'home';
  function navClass($p, $c) {
    return $p === $c
      ? 'text-white bg-blue-600 px-3 py-2 rounded-md'
      : 'text-blue-700 hover:text-blue-900 px-3 py-2 rounded-md';
  }
?>
<header class="bg-white shadow">
  <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
    <a href="?page=home" class="text-xl font-semibold">ICTWEB514</a>
    <nav class="flex items-center gap-2">
      <a href="?page=home"        class="<?= navClass('home', $currentPage) ?>"        aria-current="<?= $currentPage==='home' ? 'page' : 'false' ?>">Home</a>
      <a href="?page=addressbook" class="<?= navClass('addressbook', $currentPage) ?>" aria-current="<?= $currentPage==='addressbook' ? 'page' : 'false' ?>">Address Book</a>
      <a href="?page=menu"        class="<?= navClass('menu', $currentPage) ?>"        aria-current="<?= $currentPage==='menu' ? 'page' : 'false' ?>">Café Menu</a>
      <a href="?page=timetable"   class="<?= navClass('timetable', $currentPage) ?>"   aria-current="<?= $currentPage==='timetable' ? 'page' : 'false' ?>">Timetable</a>
    </nav>
  </div>
</header>
