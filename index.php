<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-4">
<h3 class="text-center">Admin Login</h3>
<form method="post" action="auth/login.php">
<input class="form-control mb-2" name="email" placeholder="Email" required>
<input type="password" class="form-control mb-2" name="password" placeholder="Password" required>
<button class="btn btn-primary w-100">Login</button>
</form>
</div>

</body>
</html>
