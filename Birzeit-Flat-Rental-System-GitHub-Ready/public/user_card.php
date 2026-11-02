<?php
session_start();
require_once __DIR__ . '/../includes/database.inc.php';

$user_id = $_GET['id'] ?? '';
if (!$user_id) {
    echo "<main><h2>Invalid User ID</h2></main>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("
    SELECT full_name, address_city, mobile, email
    FROM users
    WHERE id = ?
");
$stmt->execute([ $user_id ]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<main><h2>User not found</h2></main>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$active = '';
require_once __DIR__ . '/../includes/header.php';
include    __DIR__ . '/../includes/nav.php';
?>

<main>
  <h2>User Card</h2>

  <div style="border: 2px solid #333; padding: 1rem; width: 300px; text-align: center; margin: 0 auto;">
    <h3 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($user['full_name']) ?></h3>
    <p style="margin-bottom: 1rem;">City: <?= htmlspecialchars($user['address_city']) ?></p>

    <p>
      <img src="../public/icons/phone_icon.png" alt="Phone" style="width:16px; vertical-align:middle;"> 
      <?= htmlspecialchars($user['mobile']) ?>
    </p>

    <p>
      <img src="../public/icons/email_icon.png" alt="Email" style="width:16px; vertical-align:middle;"> 
      <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
        <?= htmlspecialchars($user['email']) ?>
      </a>
    </p>
  </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
