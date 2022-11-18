<?php
session_start();
unset($_SESSION['user_login']);
unset($_SESSION['admin_login']);
unset($_SESSION['dealer_login']);
unset($_SESSION['dealer_general_login']);
header("location: index.php");
?>