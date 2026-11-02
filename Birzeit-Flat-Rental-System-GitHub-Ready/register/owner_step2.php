<?php
session_start();
require_once '../includes/database.inc.php';

if (empty($_SESSION['owner'])) {
    header('Location: owner_step1.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    }
    elseif (!preg_match('/^(?=.{6,15}$)[0-9].*[a-z]$/', $_POST['pass'])) {
        $error = 'Password must be 6-15 chars, start with digit, end with lowercase letter.';
    }
    elseif ($_POST['pass'] !== $_POST['pass2']) {
        $error = 'Passwords do not match.';
    }
   
    else {
        $dup = $pdo->prepare("SELECT 1 FROM accounts WHERE username = ?");
        $dup->execute([$_POST['email']]);
        if ($dup->fetchColumn()) {
            $error = 'E-mail already in use.';
        }
    }

  
    if (empty($error)) {
        $_SESSION['owner']['email'] = $_POST['email'];
        $_SESSION['owner']['pass']  = password_hash($_POST['pass'], PASSWORD_BCRYPT);

        header('Location: owner_step3.php');
        exit;
    }
}
?>
<?php require '../includes/header.php'; include '../includes/nav.php'; ?>
<main>
  <h2>Owner Registration – Step 2/3</h2>

  <?php if ($error): ?>
     <p class="note danger"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>


  <form method="post">
      <label>Email
        <input name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </label><br>

      <label>Password
        <input type="password" name="pass" required>
      </label><br>

      <label>Confirm Password
        <input type="password" name="pass2" required>
      </label><br>

      <button class="btn-ref" type="submit">Next</button>
  </form>
</main>
<?php include '../includes/footer.php'; ?>
