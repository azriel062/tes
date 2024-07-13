<?php 
session_start();
session_unset(); // Menghapus semua variabel sesi
session_destroy(); // Menghancurkan sesi
header('Location: ../user_login.php'); // Mengarahkan ke halaman login
exit(); // Menghentikan eksekusi skrip setelah redireksi
?>
