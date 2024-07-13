<?php 
include '../../koneksi/koneksi.php';
date_default_timezone_set('Asia/Makassar'); // Setel zona waktu ke WITA
$inv = $_GET['inv'];
$current_date = date('Y-m-d H:i:s'); // Tanggal dan waktu saat ini
$tolak = mysqli_query($conn, "UPDATE produksi SET tolak = '1', terima='2', tanggal = '$current_date' WHERE invoice = '$inv'");

if($tolak){
	echo "
	<script>
	alert('PESANAN DITOLAK');
	window.location = '../produksi.php';
	</script>
	";
}
?>
