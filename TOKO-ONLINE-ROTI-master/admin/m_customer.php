<?php 
include 'header.php';

// Handle deletion and fetching data for edit
if(isset($_GET['page'])){
	$kode = mysqli_real_escape_string($conn, $_GET['kode']);
	if ($_GET['page'] == 'del') {
		$result = mysqli_query($conn, "DELETE FROM customer WHERE kode_customer = '$kode'");

		if($result){
			echo "
			<script>
			alert('DATA BERHASIL DIHAPUS');
			window.location = 'm_customer.php';
			</script>
			";
		}
	} elseif ($_GET['page'] == 'edit') {
		$query = "SELECT * FROM customer WHERE kode_customer = '$kode'";
		$result = mysqli_query($conn, $query);
		$editData = mysqli_fetch_assoc($result);
	}
}

// Handle data update
if(isset($_POST['update'])){
	$kode_customer = mysqli_real_escape_string($conn, $_POST['kode_customer']);
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	
	$query = "UPDATE customer SET nama='$nama', email='$email', telp='$telp', password='$password', username='$username' WHERE kode_customer='$kode_customer'";
	$result = mysqli_query($conn, $query);

	if($result){
		echo "
		<script>
		alert('DATA BERHASIL DIUBAH');
		window.location = 'm_customer.php';
		</script>
		";
	}
}

// Handle data insertion
if(isset($_POST['create'])){
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);

	// Generate new kode_customer
	$result = mysqli_query($conn, "SELECT kode_customer FROM customer ORDER BY kode_customer DESC LIMIT 1");
	$data = mysqli_fetch_assoc($result);
	$num = substr($data['kode_customer'], 1, 4);
	$add = (int) $num + 1;
	$kode_customer = 'C' . str_pad($add, 4, '0', STR_PAD_LEFT);
	
	$query = "INSERT INTO customer (kode_customer, nama, email, telp, password, username) VALUES ('$kode_customer', '$nama', '$email', '$telp', '$password', '$username')";
	$result = mysqli_query($conn, $query);

	if($result){
		echo "
		<script>
		alert('DATA BERHASIL DITAMBAHKAN');
		window.location = 'm_customer.php';
		</script>
		";
	}
}

// Handle search
$search = '';
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
}
?>

<div class="container">
	<h2 style=" width: 100%; border-bottom: 4px solid gray"><b>Data Customer</b></h2>
	<!-- Search Form -->
	<form method="post" action="m_customer.php">
		<div class="form-group">
			<input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama atau Email" value="<?php echo $search; ?>">
		</div>
		<button type="submit" class="btn btn-primary">Cari</button>
	</form>
	<br>

	<!-- Data Table -->
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">No</th>
				<th scope="col">Kode Customer</th>
				<th scope="col">Nama</th>
				<th scope="col">Email</th>
				<th scope="col">Telepon</th>
				<th scope="col">Password</th>
				<th scope="col">Username</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if ($search != '') {
				$query = "SELECT * FROM customer WHERE nama LIKE '%$search%' OR email LIKE '%$search%' ORDER BY kode_customer ASC";
			} else {
				$query = "SELECT * FROM customer ORDER BY kode_customer ASC";
			}
			
			$result = mysqli_query($conn, $query);
			$no = 1;
			while ($row = mysqli_fetch_assoc($result)) {
				$password_display = (strlen($row['password']) > 15) ? substr($row['password'], 0, 15) . '...' : $row['password'];
				?>
				<tr>
					<th scope="row"><?php echo $no; ?></th>
					<td><?= $row['kode_customer'];  ?></td>
					<td><?= $row['nama'];  ?></td>
					<td><?= $row['email'];  ?></td>
					<td><?= $row['telp'];  ?></td>
					<td><?= $password_display;  ?></td>
					<td><?= $row['username'];  ?></td>
					<td>
						<a href="m_customer.php?kode=<?php echo $row['kode_customer'];?>&page=del" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="glyphicon glyphicon-trash"></i> </a>
						<a href="m_customer.php?kode=<?php echo $row['kode_customer'];?>&page=edit" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i> </a>
					</td>
				</tr>
				<?php 
				$no++;
			}
			?>
		</tbody>
	</table>

	<!-- Form Create Data Customer -->
	<h3>Buat Data Customer Baru</h3>
	<form method="post" action="m_customer.php" style="width: 50%;">
		<div class="form-group">
			<label for="nama">Nama</label>
			<input type="text" name="nama" class="form-control form-control-sm" required>
		</div>
		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" name="email" class="form-control form-control-sm" required>
		</div>
		<div class="form-group">
			<label for="telp">Telepon</label>
			<input type="text" name="telp" class="form-control form-control-sm" required>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="text" name="password" class="form-control form-control-sm" required>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" class="form-control form-control-sm" required>
		</div>
		<button type="submit" name="create" class="btn btn-success btn-sm">Buat</button>
	</form>
</div>

<!-- Form Edit Data Customer -->
<?php if (isset($_GET['page']) && $_GET['page'] == 'edit' && isset($editData)): ?>
<div class="container" style="margin-top: 20px;">
	<h2 style=" width: 100%; border-bottom: 4px solid gray"><b>Edit Data Customer</b></h2>
	<form method="post" action="m_customer.php" style="width: 50%;">
		<input type="hidden" name="kode_customer" value="<?php echo $editData['kode_customer']; ?>">
		<div class="form-group">
			<label for="nama">Nama</label>
			<input type="text" name="nama" class="form-control form-control-sm" value="<?php echo $editData['nama']; ?>" required>
		</div>
		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" name="email" class="form-control form-control-sm" value="<?php echo $editData['email']; ?>" required>
		</div>
		<div class="form-group">
			<label for="telp">Telepon</label>
			<input type="text" name="telp" class="form-control form-control-sm" value="<?php echo $editData['telp']; ?>" required>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="text" name="password" class="form-control form-control-sm" value="<?php echo $editData['password']; ?>" required>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" class="form-control form-control-sm" value="<?php echo $editData['username']; ?>" required>
		</div>
		<button type="submit" name="update" class="btn btn-primary btn-sm">Update</button>
	</form>
</div>
<?php endif; ?>
<br>
<br>
<br>
<br>
<?php 
include 'footer.php';
?>
