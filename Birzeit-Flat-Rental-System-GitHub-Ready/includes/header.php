<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$active = $active ?? '';          
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>Birzeit Flat Rent</title>
  <link rel="stylesheet" href="/Birzeit-Flat-Rental-System/public/css/style.css">
</head>
<body>

<header>
  <div class="brand">
    <span>Birzeit&nbsp;Flat&nbsp;Rent</span>
  </div>

  <nav class="top-links">
<?php if (empty($_SESSION['uid'])): ?>
      <a href="/Birzeit-Flat-Rental-System/public/login.php">Log&nbsp;in</a>
 <a href="../register/">register</a>
  <?php else: ?>
      <a href="/Birzeit-Flat-Rental-System/public/index.php"
         class="user-card <?=htmlspecialchars($_SESSION['type'])?>">
        <img class="avatar" src="<?=htmlspecialchars($_SESSION['photo'])?>">
        <span><?=htmlspecialchars($_SESSION['name'])?></span>
      </a>

      <?php if ($_SESSION['type']=='customer'): ?>
<?php endif; ?>

      <a href="/Birzeit-Flat-Rental-System/public/logout.php">Log&nbsp;out</a>
  <?php endif; ?>
</nav>

</header>

<div class="layout">
