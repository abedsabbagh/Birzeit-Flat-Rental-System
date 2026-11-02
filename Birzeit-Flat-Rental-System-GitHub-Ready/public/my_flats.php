<?php
session_start();
require_once __DIR__ . '/../includes/database.inc.php';

if (empty($_SESSION['uid']) || ($_SESSION['type'] ?? '') !== 'owner') {
    header('Location: ../public/login.php');
    exit;
}

$owner_id = $_SESSION['uid'];

$stmt = $pdo->prepare("
    SELECT id, ref_number, flat_no, location, monthly_rent, is_approved, is_rented
    FROM flats
    WHERE owner_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$owner_id]);
$flats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$active = 'my_flats';
require_once __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<main>
  <h2>My Flats</h2>

  <?php if (empty($flats)): ?>
    <p>You have not listed any flats yet.</p>
  <?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Reference</th>
        <th>Flat No.</th>
        <th>Location</th>
        <th>Rent</th>
        <th>Approved</th>
        <th>Rented</th>
      </tr>
      <?php foreach ($flats as $flat): ?>
        <tr>
          <td><?= htmlspecialchars($flat['ref_number']) ?></td>
          <td><?= htmlspecialchars($flat['flat_no']) ?></td>
          <td><?= htmlspecialchars($flat['location']) ?></td>
          <td>$<?= htmlspecialchars($flat['monthly_rent']) ?></td>
          <td><?= $flat['is_approved'] ? '✅' : '❌' ?></td>
          <td><?= $flat['is_rented'] ? '✅' : '❌' ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
