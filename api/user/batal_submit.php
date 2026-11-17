<?php
session_start();
unset($_SESSION['cart_user']);
header("Location: ../../cust/katalog_homecare.php");
exit;
?>
