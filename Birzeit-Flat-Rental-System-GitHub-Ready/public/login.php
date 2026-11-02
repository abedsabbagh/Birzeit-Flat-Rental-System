<?php
session_start();
require_once '../includes/auth.php';     
$err = '';

if (!empty($_SESSION['uid'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = login($_POST['username'], $_POST['password']);
    if ($ok) {
        header('Location: index.php');   
        exit;
    } else {
        $err = 'Incorrect username or password';
    }
}

$active = '';                           
require_once '../includes/header.php';
include     '../includes/nav.php';
?>
<main>
  <h2>Log in</h2>

  <?php if ($err): ?>
    <p class="note danger"><?= htmlspecialchars($err) ?></p>
  <?php endif; ?>

  <form method="post" style="max-width:400px">
    <div class="required"><label>Email
      <input type="email" name="username" required></label></div>

    <div class="required"><label>Password
      <input type="password" name="password" required></label></div>

    <button class="btn-ref" type="submit">Login</button>
  </form>
</main>
<?php include '../includes/footer.php'; ?>
