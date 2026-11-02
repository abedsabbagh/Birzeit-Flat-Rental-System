<?php
session_start();
require_once __DIR__ . '/../includes/database.inc.php';

if (empty($_SESSION['uid']) || ($_SESSION['type'] ?? '') !== 'customer') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    empty($_POST['flat_id']) ||
    empty($_POST['start_date']) ||
    empty($_POST['end_date']) ||
    empty($_POST['total_amount']) ||
    ($_POST['demo_confirm'] ?? '') !== '1') {

    echo "<main><h2>Invalid request.</h2></main>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$flat_id      = $_POST['flat_id'];
$customer_id  = $_SESSION['uid'];
$start_date   = $_POST['start_date'];
$end_date     = $_POST['end_date'];
$total_amount = $_POST['total_amount'];
// No card data is collected. This is a demonstration-only rental.
$card_number  = 'DEMO_ONLY';
$ref_no       = 'RENT' . date('YmdHis') . rand(1000, 9999);

$stmt = $pdo->prepare("
    INSERT INTO rentals
    (flat_id, customer_id, rent_start, rent_end, total_amount, credit_card, status, ref_no)
    VALUES
    (?, ?, ?, ?, ?, ?, 'pending', ?)
");

$stmt->execute([
    $flat_id,
    $customer_id,
    $start_date,
    $end_date,
    $total_amount,
    $card_number,
    $ref_no
]);

$pdo->prepare("UPDATE flats SET is_rented = 1 WHERE id = ?")->execute([ $flat_id ]);

$active = '';
require_once __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<main>
  <h2>Demo Rental Recorded</h2>
  <p>No payment has been processed.</p>
  <p>Your rental reference number is: <strong><?= htmlspecialchars($ref_no) ?></strong></p>
  <p>Thank you for using Birzeit Flat Rent.</p>
  <a href="../public/index.php" class="btn-ref">Back to Home</a>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
