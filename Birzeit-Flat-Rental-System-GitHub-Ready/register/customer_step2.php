<?php
session_start();
require_once '../includes/database.inc.php';

if (empty($_SESSION['reg'])) {
    header('Location: customer_step1.php');
    exit;
}

$err = '';

if ($_SERVER['REQUEST_METHOD']==='POST'){
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $err = 'Invalid mail';
    } elseif (!preg_match('/^(?=.{6,15}$)[0-9].*[a-z]$/', $_POST['pass'])){
        $err = 'Your password needs to be between 6 and 15 characters.  It must begin with a number and end with a lowercase letter.';
    } elseif ($_POST['pass'] !== $_POST['pass2']){
        $err = 'The Passwords do not match';
    } else {
        $dup = $pdo->prepare("SELECT 1 FROM accounts WHERE username = ?");
        $dup->execute([$_POST['email']]);
        if ($dup->fetchColumn()) {
            $err = 'The email address is already in use';
        } else {
        $_SESSION['reg']['email'] = $_POST['email'];
        $_SESSION['reg']['pass']  = password_hash($_POST['pass'], PASSWORD_BCRYPT);
        header('Location: customer_step3.php');
        exit;
    }
}}

require_once '../includes/header.php';
include '../includes/nav.php';
?>
<main>
  <h2> Customer Registration - Step 2/3 </h2>
<?php if ($err): ?>
    <p class="note danger"><?= htmlspecialchars($err) ?></p>
  <?php endif; ?>
  <form method="post">
    <div class="required"><label>Email
      <input name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required></label></div>

    <div class="required"><label>Password
      <input type="password" name="pass" required></label></div>

    <div class="required"><label>Confirm password
      <input type="password" name="pass2" required></label></div>

    <button class="btn-ref" type="submit">Next</button>
  </form>
</main>
<?php include '../includes/footer.php'; ?>
