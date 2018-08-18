<?php
$title = basename($_SERVER['SCRIPT_NAME'], '.php');
$title = str_replace('_', ' ', $title);
if ($title == 'index') {
  $title = 'home';
  }
$title = ucwords($title);
?>