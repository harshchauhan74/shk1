<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
    exit;
}
include "../config/db.php";

/* COUNTS */
$emp = $conn->query("SELECT COUNT(*) c FROM employee")->fetch_assoc()['c'];
$role = $conn->query("SELECT COUNT(*) c FROM employeerole")->fetch_assoc()['c'];
$product = $conn->query("SELECT COUNT(*) c FROM product")->fetch_assoc()['c'];

/* TODAY DATA */
$today = date('Y-m-d');

$today_salary = $conn->query(
    "SELECT IFNULL(SUM(total_salary),0) t 
     FROM dailysalary WHERE work_date='$today'"
)->fetch_assoc()['t'];

$today_sales = $conn->query(
    "SELECT IFNULL(SUM(total_a),0) t 
     FROM sales WHERE s_date='$today'"
)->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-box{
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.card-box h3{
    font-size: 26px;
}
.nav-card{
    transition: 0.3s;
}
.nav-card:hover{
    transform: translateY(-4px);
}
</style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Khakhara Admin Panel</span>
    <a href="../auth/logout.php" class="btn btn-sm btn-outline-light">Logout</a>
</nav>

<div class="container my-4">

<h4 class="mb-4">Dashboard Overview</h4>

<!-- STATS -->
<div class="row g-4">
    <div class="col-md-3 col-6">
        <div class="card card-box text-center p-3">
            <small>Total Employees</small>
            <h3><?= $emp ?></h3>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card card-box text-center p-3">
            <small>Total Roles</small>
            <h3><?= $role ?></h3>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card card-box text-center p-3">
            <small>Total Products</small>
            <h3><?= $product ?></h3>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card card-box text-center p-3">
            <small>Today's Salary (₹)</small>
            <h3><?= number_format($today_salary,2) ?></h3>
        </div>
    </div>
</div>

<!-- SALES -->
<div class="row g-4 mt-1">
    <div class="col-md-3 col-6">
        <div class="card card-box text-center p-3">
            <small>Today's Sales (₹)</small>
            <h3><?= number_format($today_sales,2) ?></h3>
        </div>
    </div>
</div>

<hr class="my-4">

<!-- QUICK NAVIGATION -->
<h5 class="mb-3">Quick Actions</h5>

<div class="row g-4">
    <div class="col-md-3 col-6">
        <a href="employees.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">👷 Employees</div>
        </a>
    </div>

    <div class="col-md-3 col-6">
        <a href="roles.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">⚙️ Roles</div>
        </a>
    </div>

    <div class="col-md-3 col-6">
        <a href="products.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">📦 Products</div>
        </a>
    </div>

    <div class="col-md-3 col-6">
        <a href="daily_work.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">🧮 Daily Salary</div>
        </a>
    </div>

    <div class="col-md-3 col-6">
        <a href="daily_salary.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">📅 Daily Work</div>
        </a>
    </div>

    <div class="col-md-3 col-6">
        <a href="sales.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">💰 Sales</div>
        </a>
    </div>

    <div class="col-md-3 col-6">
        <a href="production.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">🏭 Production</div>
        </a>
    </div>
        
    <div class="col-md-3 col-6">
        <a href="reports.php" class="text-decoration-none">
            <div class="card nav-card p-3 text-center">📊 Reports</div>
        </a>
    </div>
</div>

</div>

</body>
</html>
