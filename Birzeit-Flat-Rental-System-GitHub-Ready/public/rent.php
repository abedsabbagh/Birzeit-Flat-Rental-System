<?php
session_start();
require_once __DIR__ . '/../includes/database.inc.php';

if (empty($_SESSION['uid']) || ($_SESSION['type'] ?? '') !== 'customer') {
    // Save redirect URL
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$flat_id = $_GET['flat'] ?? '';
if (!$flat_id) {
    header('Location: search.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT f.*, u.full_name AS owner_name, u.national_id AS owner_id,
           CONCAT(u.address_street, ', ', u.address_city) AS owner_address
    FROM flats f
    JOIN users u ON u.id = f.owner_id
    WHERE f.id = ? AND f.is_approved = 1 AND f.is_rented = 0
");
$stmt->execute([ $flat_id ]);
$flat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flat) {
    echo "<main><h2>Flat not available for rent.</h2></main>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['start_date'])) {

    $active = '';
    require_once __DIR__ . '/../includes/header.php';
    include    __DIR__ . '/../includes/nav.php';
    ?>

    <main>
      <h2>Rent Flat: <?= htmlspecialchars($flat['ref_number']) ?></h2>

      <form method="post">
        <!--Apartment details -->
        <fieldset disabled>
          <legend>Flat Details</legend>
          <p>Reference #: <?= htmlspecialchars($flat['ref_number']) ?></p>
          <p>Flat No.: <?= htmlspecialchars($flat['flat_no']) ?></p>
          <p>Location: <?= htmlspecialchars($flat['location']) ?></p>
          <p>Address: <?= htmlspecialchars($flat['address_street']) ?></p>
          <p>Monthly Rent: $<?= htmlspecialchars($flat['monthly_rent']) ?></p>
          <p>Owner: <?= htmlspecialchars($flat['owner_name']) ?> (<?= htmlspecialchars($flat['owner_id']) ?>)</p>
          <p>Owner Address: <?= htmlspecialchars($flat['owner_address']) ?></p>
        </fieldset>

        <!-- Rental period -->
        <fieldset>
          <legend>Rental Period</legend>
          <label>Start Date
            <input type="date" name="start_date" required>
          </label><br>
          <label>End Date
            <input type="date" name="end_date" required>
          </label>
        </fieldset>

        <button type="submit">Continue →</button>
      </form>
    </main>

    <?php
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$start_date = $_POST['start_date'];
$end_date   = $_POST['end_date'];

$start = new DateTime($start_date);
$end   = new DateTime($end_date);
$interval = $start->diff($end);
$months = max(1, $interval->m + ($interval->y * 12));
$total_amount = $months * $flat['monthly_rent'];

$active = '';
require_once __DIR__ . '/../includes/header.php';
include    __DIR__ . '/../includes/nav.php';
?>

<main>
  <h2>Confirm Rent: <?= htmlspecialchars($flat['ref_number']) ?></h2>

  <form method="post" action="rent_confirm.php">
    <!-- Hidden inputs -->
    <input type="hidden" name="flat_id" value="<?= htmlspecialchars($flat_id) ?>">
    <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
    <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
    <input type="hidden" name="total_amount" value="<?= htmlspecialchars($total_amount) ?>">

    <!-- Summary -->
    <fieldset disabled>
      <legend>Summary</legend>
      <p>Flat: <?= htmlspecialchars($flat['ref_number']) ?></p>
      <p>Rental Period: <?= htmlspecialchars($start_date) ?> → <?= htmlspecialchars($end_date) ?></p>
      <p>Total Amount: $<?= htmlspecialchars($total_amount) ?></p>
    </fieldset>

    <!-- Demonstration flow only. This application does not process payments. -->
    <fieldset>
      <legend>Demo confirmation — no payment will be processed</legend>
      <p>This educational prototype records a pending rental for demonstration purposes only.</p>
      <label><input type="checkbox" name="demo_confirm" value="1" required>
        I understand this is a simulated booking with no payment.
      </label>
    </fieldset>

    <button type="submit">Record Demo Rental</button>
  </form>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
