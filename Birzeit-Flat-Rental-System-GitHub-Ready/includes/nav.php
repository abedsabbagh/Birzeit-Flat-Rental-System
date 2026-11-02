<aside class="side-nav">
  <ul>
    <li><a href="/Birzeit-Flat-Rental-System/public/index.php"
           class="<?=$active=='home'?'active':''?>">Home</a></li>
    <li><a href="/Birzeit-Flat-Rental-System/public/search.php"
           class="<?=$active=='search'?'active':''?>">Searching for an apartment</a></li>
    <?php if(empty($_SESSION['uid'])): ?>
      <li><a href="../register/" <?php if($active=='register') echo 'class="active"'; ?>>register</a></li>
      <li><a href="/Birzeit-Flat-Rental-System/public/login.php">Log in</a></li>
    <?php endif;?>

    <?php if($_SESSION['type']??''=='customer'): ?>
      <li><a href="/Birzeit-Flat-Rental-System/public/my_rentals.php"
             class="<?=$active=='rentals'?'active':''?>">My rents</a></li>
    <?php elseif($_SESSION['type']??''=='owner'): ?>
      <li><a href="/Birzeit-Flat-Rental-System/public/my_flats.php"
             class="<?=$active=='flats'?'active':''?>">My apartments</a></li>
    <?php elseif($_SESSION['type']??''=='manager'): ?>
      <li><a href="/Birzeit-Flat-Rental-System/public/search.php"
             class="<?=$active=='inq'?'active':''?>">inquiry</a></li>
    <?php endif;?>
  </ul>
</aside>
