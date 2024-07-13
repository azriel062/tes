<?php 
	include 'header.php';
?>

<div class="container" style="padding-bottom: 250px;">
	<h2 style="width: 100%; border-bottom: 4px solid hsl(154, 100%, 32%)"><b>Login</b></h2>

	<form action="proses/login.php" method="POST">
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" id="username" placeholder="Username" name="username" style="width: 500px;" required>
		</div>
		
		<div class="form-group">
			<label for="password">Password</label>
			<div class="input-group" style="width: 500px;">
				<input type="password" class="form-control" id="password" placeholder="Password" name="pass" required>
				<div class="input-group-append">
					<button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
						<i class="fa fa-eye" id="togglePasswordIcon"></i>
					</button>
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-success">Login</button>
		<a href="register.php" class="btn btn-primary">Daftar</a>
		<button type="button" class="btn btn-secondary" id="adminButton" title="Admin">
			<i class="fa fa-user-shield"></i>
		</button>
	</form>
</div>

<?php 
	include 'footer.php';
?>

<script>
function togglePassword(fieldId) {
	const field = document.getElementById(fieldId);
	const icon = document.getElementById('togglePasswordIcon');
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

document.getElementById('adminButton').addEventListener('click', function() {
	window.location.href = '/TOKO-ONLINE-ROTI-master/admin/index.php';
});
</script>

<!-- Add FontAwesome for icons (you can include this in your header.php if preferred) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
