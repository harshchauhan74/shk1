<?php
session_start();
include "../config/db.php";

$email = $_POST['email'];
$pass  = $_POST['password'];

$q = $conn->query("SELECT * FROM admin WHERE email='$email'");
$row = $q->fetch_assoc();

if ($row && $pass === $row['password']) {
    $_SESSION['admin'] = $row['a_id'];
    header("Location: ../admin/dashboard.php");
    exit;
} else {
    echo "Invalid Login";
}
