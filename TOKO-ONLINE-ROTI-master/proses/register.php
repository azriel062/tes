<?php
include '../koneksi/koneksi.php';

// Fetch the last customer code
$kode = mysqli_query($conn, "SELECT kode_customer FROM customer ORDER BY kode_customer DESC LIMIT 1");
$data = mysqli_fetch_assoc($kode);
$num = substr($data['kode_customer'], 1, 4);
$add = (int) $num + 1;

// Generate new customer code
if(strlen($add) == 1){
    $format = "C000".$add;
} elseif(strlen($add) == 2){
    $format = "C00".$add;
} elseif(strlen($add) == 3){
    $format = "C0".$add;
} else {
    $format = "C".$add;
}

// Retrieve and sanitize input
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$tlp = mysqli_real_escape_string($conn, $_POST['telp']);
$konfirmasi = mysqli_real_escape_string($conn, $_POST['konfirmasi']);

if($password == $konfirmasi){
    // Check if username already exists
    $cek = mysqli_query($conn, "SELECT username FROM customer WHERE username = '$username'");
    $jml = mysqli_num_rows($cek);

    if($jml == 1){
        echo "
        <script>
        alert('USERNAME SUDAH DIGUNAKAN');
        window.location = '../register.php';
        </script>
        ";
        die;
    }

    // Insert new customer
    $stmt = $conn->prepare("INSERT INTO customer (kode_customer, nama, email, username, telp, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $format, $nama, $email, $username, $tlp, $password);
    $result = $stmt->execute();

    if($result){
        echo "
        <script>
        alert('REGISTER BERHASIL');
        window.location = '../user_login.php';
        </script>
        ";
    } else {
        echo "
        <script>
        alert('REGISTER GAGAL');
        window.location = '../register.php';
        </script>
        ";
    }

    $stmt->close();
} else {
    echo "
    <script>
    alert('KONFIRMASI PASSWORD TIDAK SAMA');
    window.location = '../register.php';
    </script>
    ";
}

$conn->close();
?>
