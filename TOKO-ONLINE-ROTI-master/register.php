<?php 
include 'header.php';
?>

<div class="container" style="padding-bottom: 250px;">
	<h2 style="width: 100%; border-bottom: 4px solid hsl(154, 100%, 32%)"><b>Register</b></h2>
	<form action="proses/register.php" method="POST">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="nama">Nama</label>
					<input type="text" class="form-control" id="nama" placeholder="Nama" name="nama" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="telp">No Telepon</label>
					<input type="text" class="form-control" id="telp" placeholder="+62" name="telp" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="password">Password</label>
					<div class="input-group">
						<input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
						<div class="input-group-append">
							<button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
								<i class="fa fa-eye" id="togglePasswordIcon"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="konfirmasi">Konfirmasi Password</label>
					<div class="input-group">
						<input type="password" class="form-control" id="konfirmasi" placeholder="Konfirmasi Password" name="konfirmasi" required>
						<div class="input-group-append">
							<button type="button" class="btn btn-outline-secondary" onclick="togglePassword('konfirmasi')">
								<i class="fa fa-eye" id="toggleKonfirmasiPasswordIcon"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-success">Register</button>
	</form>
</div>

<?php 
include 'footer.php';
?>

<script>
function togglePassword(fieldId) {
	const field = document.getElementById(fieldId);
	const icon = document.getElementById('toggle' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1) + 'Icon');
	if (field.type === "password") {
		field.type = "text";
		icon.classList.remove('fa-eye');
		icon.classList.add('fa-eye-slash');
	} else {
		field.type = "password";
		icon.classList.remove('fa-eye-slash');
		icon.classList.add('fa-eye');
	}
}
</script>

<!-- Add FontAwesome for icons (you can include this in your header.php if preferred) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
