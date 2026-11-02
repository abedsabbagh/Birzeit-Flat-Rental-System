<?php
session_start();
require_once '../includes/database.inc.php';

if (empty($_SESSION['reg'])) {
    header('Location: customer_step1.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nid   = $_SESSION['reg']['nid'];
    $email = $_SESSION['reg']['email'];

    $dup = $pdo->prepare("SELECT 1 FROM users WHERE national_id = ?");
    $dup->execute([$nid]);
    if ($dup->fetchColumn()) {
        $_SESSION['error'] = "The ID number is already in use. Please change it.";
        header('Location: customer_step1.php');
        exit;
    }

    $dup = $pdo->prepare("SELECT 1 FROM accounts WHERE username = ?");
    $dup->execute([$email]);
    if ($dup->fetchColumn()) {
        $_SESSION['error'] = "The email address is already in use. Please change it.";
        header('Location: customer_step2.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        /* users */
        $pdo->prepare("
            INSERT INTO users
              (user_type,national_id,full_name,dob,email,mobile,phone,
               address_flat,address_street,address_city,postal_code)
            VALUES
              ('customer', :nid, :name, :dob, :email, :mob, :ph,
               :flat, :street, :city, :postal)
        ")->execute([
            ':nid'    => $nid,
            ':name'   => $_SESSION['reg']['name'],
            ':dob'    => $_SESSION['reg']['dob'],
            ':email'  => $email,
            ':mob'    => $_SESSION['reg']['mobile'],
            ':ph'     => $_SESSION['reg']['phone'],
            ':flat'   => $_SESSION['reg']['flatno'],
            ':street' => $_SESSION['reg']['street'],
            ':city'   => $_SESSION['reg']['city'],
            ':postal' => $_SESSION['reg']['postal']
        ]);

        $uid = $pdo->lastInsertId();

        /* accounts */
        $pdo->prepare("
            INSERT INTO accounts(user_id,username,password_hash)
            VALUES(:uid, :u, :p)
        ")->execute([
            ':uid' => $uid,
            ':u'   => $email,
            ':p'   => $_SESSION['reg']['pass']
        ]);

        $pdo->commit();

        $custId = sprintf('%09d', $uid);
        require_once '../includes/header.php';
        include        '../includes/nav.php';

        echo "
        <main>
          <h2>Congratulations!</h2>
          <p>Welcome {$_SESSION['reg']['name']},Your customer number is
             <strong>$custId</strong></p>
          <a class='btn-ref' href='/Birzeit-Flat-Rental-System/public/login.php'>Log in</a>
        </main>";

        unset($_SESSION['reg']);
        include '../includes/footer.php';
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();                      
        $_SESSION['error'] = "An unexpected error occurred.: ".$e->getMessage();
        header('Location: customer_step1.php');
        exit;
    }
}


require_once '../includes/header.php';
include        '../includes/nav.php';
?>
<main>
  <h2>Confirm data – Step 3/3</h2>

  <?php if (!empty($_SESSION['error'])): ?>
      <p class="note danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
  <?php endif; ?>

  <form method="post">
      <div class="review-data">
        <?php
        foreach ($_SESSION['reg'] as $k => $v) {
            if (in_array($k, ['pass','pass2'])) continue;   
            echo "<p><strong>$k:</strong> ".htmlspecialchars($v)."</p>";
        }
        ?>
      </div>

      <button class="btn-ref" type="submit">Confirm registration</button>
  </form>
</main>
<?php include '../includes/footer.php'; ?>
