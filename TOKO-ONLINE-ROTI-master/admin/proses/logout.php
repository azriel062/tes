<?php 
session_start();
unset($_SESSION['admin']);

if(!isset($_SESSION['admin'])){
    header('Location: /TOKO-ONLINE-ROTI-master/user_login.php');
    exit(); // Ensure script execution stops after redirect
}
?>
