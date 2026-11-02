<?php
session_start();                
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $_SESSION['reg'] = $_POST;  
    header('Location: customer_step2.php');
    exit;                       
}
$active = '';                     
require_once '../includes/header.php';
include '../includes/nav.php';


?>
<main>
<h2> Customer Registration - Step 1/3 </h2>
<form method="post" class="reg-form">
    <div class="required"><label>National ID number
      <input name="nid" pattern="\d{9}" required></label></div>

    <div class="required"><label>Full Name
      <input name="name" pattern="[\p{L}\s]{3,}" required></label></div>

    <label>Apartment/house number<input name="flatno"></label>
    <label>Street name <input name="street"></label>
    <label>City <input name="city"></label>
    <label>postcode <input name="postal"></label>

    <div class="required"><label>date of birth
      <input type="date" name="dob" required></label></div>

    <label>Mobile<input name="mobile"></label>
    <label>Home phone<input name="phone"></label>

    <button class="btn-ref" type="submit">Next</button>
  </form>
</main>
<?php include '../includes/footer.php'; ?>
