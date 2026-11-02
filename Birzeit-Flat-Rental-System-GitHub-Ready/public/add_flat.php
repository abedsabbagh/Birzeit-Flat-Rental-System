<?php
session_start();
require_once '../includes/database.inc.php';

if (empty($_SESSION['uid']) || ($_SESSION['type'] ?? '') !== 'owner') {
    header('Location: ../public/login.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref = trim($_POST['ref_number']);
    $flat = trim($_POST['flat_no']);
    $loc = trim($_POST['location']);
    $street = trim($_POST['address_street']);
    $rent = floatval($_POST['monthly_rent']);

    if (!$ref || !$flat || !$loc || !$street || $rent <= 0) {
        $err = "All fields are required and rent must be a positive number.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO flats (owner_id, ref_number, flat_no, location, address_street, monthly_rent, is_approved, is_rented)
                               VALUES (:oid, :ref, :flat, :loc, :street, :rent, 0, 0)");
        $stmt->execute([
            ':oid'    => $_SESSION['uid'],
            ':ref'    => $ref,
            ':flat'   => $flat,
            ':loc'    => $loc,
            ':street' => $street,
            ':rent'   => $rent
        ]);
        header("Location: my_flats.php");
        exit;
    }
}

require_once '../includes/header.php';
include '../includes/nav.php';
?>

<main>
  <h2>Add New Flat</h2>

  <?php if ($err): ?>
    <p class="note danger"><?= htmlspecialchars($err) ?></p>
  <?php endif; ?>

  <form method="post">
    <div class="required"><label>Reference Number:
      <input name="ref_number" required></label></div>

    <div class="required"><label>Flat Number:
      <input name="flat_no" required></label></div>

    <div class="required"><label>Location:
      <input name="location" required></label></div>

    <div class="required"><label>Street Address:
      <input name="address_street" required></label></div>

    <div class="required"><label>Monthly Rent ($):
      <input name="monthly_rent" type="number" min="1" step="any" required></label></div>

    <button class="btn-ref" type="submit">Add Flat</button>
  </form>
</main>

<?php include '../includes/footer.php'; ?>
