<?php 
session_start();
include '../../koneksi/koneksi.php';

$username = $_POST['user'];
$pass = $_POST['pass'];

// Check if username and password are both 'logout'
if($username === 'logout' && $pass === 'logout'){
    header('Location: ../index.php');
    exit(); // Ensure script execution stops after redirect
}

// cek user
$result = mysqli_query($conn, "SELECT * FROM admin where username = '$username'");
$row = mysqli_fetch_assoc($result);
$user = $row['username'];
$ps = $row['password'];

if($username == $user){
    if(password_verify($pass, $ps)){
        $_SESSION["admin"] = true;
        header('Location: ../halaman_utama.php');
        exit(); // Ensure script execution stops after redirect
    } else {
        echo "
        <script>
        alert('USERNAME/PASSWORD SALAH');
        window.location = '../index.php';
        </script>
        ";
    }
} else {
    echo "
    <script>
    alert('USERNAME/PASSWORD SALAH');
    window.location = '../index.php';
    </script>
    ";
}
?>
