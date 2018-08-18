<?php $currentPage = basename($_SERVER['SCRIPT_NAME']); ?>
<ul id="nav">
  <li><a href="index.php" <?php if ($currentPage == 'index.php') {echo 'id="here"';} ?>>Home</a></li>
  <li><a href="journal.php" <?php if ($currentPage == 'journal.php') {echo 'id="here"';} ?>>Journal</a></li>
  <li><a href="gallery.php" <?php if ($currentPage == 'gallery.php') {echo 'id="here"';} ?>>Gallery</a></li>
  <li><a href="contact.php" <?php if ($currentPage == 'contact.php') {echo 'id="here"';} ?>>Contact</a></li>
</ul>
