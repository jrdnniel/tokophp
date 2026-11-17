<?php
session_start();
unset($_SESSION['cart_fo']);
unset($_SESSION['nama_customer_fo']);
header("Location: ../../fo/katalog_homecare.php");
exit;
?>
