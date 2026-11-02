<?php
session_start();
$active = 'register';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';
?>
<main>
  <h2>Register to Birzeit Flat Rent</h2>
  <p>Please choose your registration type:</p>
  <ul class="register-select">
    <li><a href="customer_step1.php" class="btn-ref">Register as Customer</a></li>
    <li><a href="owner_step1.php"    class="btn-ref">Register as Owner</a></li>
  </ul>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
