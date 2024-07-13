<?php 
session_start();
include '../koneksi/koneksi.php';

$username = trim(mysqli_real_escape_string($conn, $_POST['username']));
$password = trim(mysqli_real_escape_string($conn, $_POST['pass']));

if (empty($username) || empty($password)) {
    echo "
    <script>
    alert('Username atau Password tidak boleh kosong');
    window.location = '../user_login.php';
    </script>
    ";
    die;
}

$cek = mysqli_query($conn, "SELECT * FROM customer WHERE username = '$username'");
$jml = mysqli_num_rows($cek);

if($jml == 1){
    $row = mysqli_fetch_assoc($cek);
    // Directly compare plaintext password
    if($password == $row['password']){
        $_SESSION['user'] = $row['nama'];
        $_SESSION['kd_cs'] = $row['kode_customer'];
        header('Location: ../index.php');
        exit();
    } else {
        echo "
        <script>
        alert('USERNAME/PASSWORD SALAH');
        window.location = '../user_login.php';
        </script>
        ";
        die;
    }
} else {
    echo "
    <script>
    alert('USERNAME/PASSWORD SALAH');
    window.location = '../user_login.php';
    </script>
    ";
    die;
}
?>
