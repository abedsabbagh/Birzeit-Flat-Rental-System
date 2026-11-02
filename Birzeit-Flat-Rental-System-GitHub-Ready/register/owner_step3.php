<?php
session_start();
require_once '../includes/database.inc.php';

if (empty($_SESSION['owner'])) {
    header('Location: owner_step1.php');
    exit;
}

if (!empty($_SESSION['owner_inserted'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE national_id = ?");
    $stmt->execute([$_SESSION['owner']['nid']]);
    if ($stmt->fetchColumn()) {
        $_SESSION['error'] = "National ID already registered.";
        header('Location: owner_step1.php');
        exit;
    }

    $pdo->beginTransaction();

    $pdo->prepare("
        INSERT INTO users
        (user_type, national_id, full_name, dob, email, mobile, phone,
         address_flat, address_street, address_city, postal_code,
         bank_name, bank_branch, bank_account)
        VALUES
        ('owner', :nid, :name, :dob, :email, :mob, :ph,
         :flat, :street, :city, :postal,
         :bname, :bbranch, :bacc)
    ")->execute([
        ':nid'    => $_SESSION['owner']['nid'],
        ':name'   => $_SESSION['owner']['name'],
        ':dob'    => $_SESSION['owner']['dob'],
        ':email'  => $_SESSION['owner']['email'],
        ':mob'    => $_SESSION['owner']['mobile'],
        ':ph'     => $_SESSION['owner']['phone'],
        ':flat'   => $_SESSION['owner']['flatno'],
        ':street' => $_SESSION['owner']['street'],
        ':city'   => $_SESSION['owner']['city'],
        ':postal' => $_SESSION['owner']['postal'],
        ':bname'  => $_SESSION['owner']['bank_name'],
        ':bbranch'=> $_SESSION['owner']['bank_branch'],
        ':bacc'   => $_SESSION['owner']['bank_account']
    ]);

    $uid = $pdo->lastInsertId();

    $pdo->prepare("
        INSERT INTO accounts (user_id, username, password_hash)
        VALUES (:uid, :u, :p)
    ")->execute([
        ':uid' => $uid,
        ':u'   => $_SESSION['owner']['email'],
        ':p'   => $_SESSION['owner']['pass']
    ]);

    $pdo->commit();

    $ownerId = sprintf('%09d', $uid);
    $name    = htmlspecialchars($_SESSION['owner']['name']);
    
    $_SESSION['owner_inserted'] = true;

    unset($_SESSION['owner']);
    unset($_SESSION['owner']);

    require '../includes/header.php';
    echo "<main><h2>Congratulations!</h2>
          <p>Welcome $name, your Owner ID is <strong>$ownerId</strong></p>
          <a class='btn-ref' href='/Birzeit-Flat-Rental-System/public/login.php'>Log In</a>
          </main>";
    include '../includes/footer.php';
    exit;
}

require '../includes/header.php';
include '../includes/nav.php';
?>
<main>
  <h2>Confirm Data – Step 3/3</h2>

  <div class="review-data">
    <?php
    foreach ($_SESSION['owner'] as $k => $v) {
        if ($k === 'pass' || $k === 'pass2') continue;
        echo "<p><strong>$k:</strong> " . htmlspecialchars($v) . "</p>";
    }
    ?>
  </div>

  <form method="post">
      <button class="btn-ref" type="submit">Confirm Registration</button>
  </form>
</main>
<?php include '../includes/footer.php'; ?>
