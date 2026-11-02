<?php
session_start();
require_once __DIR__ . '/../includes/database.inc.php';

if (empty($_SESSION['uid']) || ($_SESSION['type'] ?? '') !== 'owner') {
    header('Location: login.php');
    exit;
}

$owner_id = $_SESSION['uid'];

$stmt = $pdo->prepare("
    SELECT r.*, f.ref_number, f.flat_no, u.full_name AS customer_name, u.id AS customer_id
    FROM rentals r
    JOIN flats f ON r.flat_id = f.id
    JOIN users u ON r.customer_id = u.id
    WHERE f.owner_id = ?
    ORDER BY r.rent_start DESC
");
$stmt->execute([ $owner_id ]);
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);

$active = '';
require_once __DIR__ . '/../includes/header.php';
include    __DIR__ . '/../includes/nav.php';
?>

<main>
  <h2>My Flats Rentals</h2>

  <?php if (empty($rentals)): ?>
    <p>No rentals yet for your flats.</p>
  <?php else: ?>
    <table class="results-table">
      <thead>
        <tr>
          <th>Flat Ref #</th>
          <th>Flat No</th>
          <th>Rental Start</th>
          <th>Rental End</th>
          <th>Total Amount</th>
          <th>Status</th>
          <th>Customer</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rentals as $rental): ?>
          <tr class="<?= 'status-' . strtolower($rental['status']) ?>">
            <td>
              <a href="flat.php?id=<?= htmlspecialchars($rental['flat_id']) ?>" target="_blank" class="btn-ref">
                <?= htmlspecialchars($rental['ref_number']) ?>
              </a>
            </td>
            <td><?= htmlspecialchars($rental['flat_no']) ?></td>
            <td><?= htmlspecialchars($rental['rent_start']) ?></td>
            <td><?= htmlspecialchars($rental['rent_end']) ?></td>
            <td>$<?= htmlspecialchars($rental['total_amount']) ?></td>
            <td><?= htmlspecialchars($rental['status']) ?></td>
            <td>
              <a href="user_card.php?id=<?= htmlspecialchars($rental['customer_id']) ?>" target="_blank" class="btn-ref">
                <?= htmlspecialchars($rental['customer_name']) ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
