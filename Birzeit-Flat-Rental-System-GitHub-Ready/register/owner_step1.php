<?php
session_start();
require_once '../includes/database.inc.php';

$error = '';

if (!empty($_SESSION['uid'])) {
    header('Location: ../logout.php');   
    exit;
}
if (!empty($_SESSION['error'])) {
  echo '<p class="error">'.htmlspecialchars($_SESSION['error']).'</p>';
  unset($_SESSION['error']);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nid = $_POST['nid'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE national_id = ?");
    $stmt->execute([$nid]);

    if ($stmt->fetchColumn() > 0) {
        $error = "This National ID is already registered. Please use another.";
    } else {
       $_SESSION['owner'] = [
    'nid'    => $_POST['nid'],
    'name'   => $_POST['name'],
    'dob'    => $_POST['dob'],
    'mobile' => $_POST['mobile'],
    'phone'  => $_POST['phone'],
    'flatno' => $_POST['flatno'],
    'street' => $_POST['street'],
    'city'   => $_POST['city'],
    'postal' => $_POST['postal'],
    'bank_name'   => $_POST['bank_name'],
    'bank_branch' => $_POST['bank_branch'],
    'bank_account'=> $_POST['bank_account']
];
        header('Location: owner_step2.php');
        exit;
    }
}
require_once '../includes/header.php';
include '../includes/nav.php';
?>
<main>
<h2>Owner Registration – Step 1/3</h2>
<?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
<form method="post">
    <div class="required"><label>National ID number
      <input name="nid" pattern="\d{9}" required></label></div>

    <div class="required"><label>Full Name
      <input name="name" pattern="[\p{L}\s]{3,}" required></label></div>

    <label>Housing Unit/Number<input name="flatno"></label>
    <label>Street name <input name="street"></label>
    <label>City <input name="city"></label>
    <label>postcode <input name="postal"></label>

    <div class="required"><label>Birth date
      <input type="date" name="dob" required></label></div>

    <label>Mobile <input name="mobile"></label>
    <label>Home phone<input name="phone"></label>

    <h3>Bank details</h3>
    <div class="required"><label>Bank Name
      <input name="bank_name" required></label></div>
    <div class="required"><label>Branch
      <input name="bank_branch" required></label></div>
    <div class="required"><label>Account Number
      <input name="bank_account" required></label></div>

    <button class="btn-ref">Next</button>
  </form>
</main>
<?php include '../includes/footer.php'; ?>
