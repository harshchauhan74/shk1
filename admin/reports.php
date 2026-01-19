<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
    exit;
}
include "../config/db.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{ box-shadow:0 4px 10px rgba(0,0,0,0.08); }
</style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Reports</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">

<!-- ================= SALARY REPORT ================= -->
<div class="card p-3 mb-4">
<h5>Salary Report</h5>

<form class="row g-2 mb-3">
    <div class="col-md-2">
        <input type="date" name="s_from" class="form-control" value="<?= $_GET['s_from'] ?? '' ?>">
    </div>
    <div class="col-md-2">
        <input type="date" name="s_to" class="form-control" value="<?= $_GET['s_to'] ?? '' ?>">
    </div>

    <div class="col-md-3">
        <select name="emp_id" class="form-control">
            <option value="">All Employees</option>
            <?php
            $e=$conn->query("SELECT * FROM employee");
            while($r=$e->fetch_assoc()){
                $sel=(($_GET['emp_id']??'')==$r['e_id'])?'selected':'';
                echo "<option value='{$r['e_id']}' $sel>{$r['e_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="role_id" class="form-control">
            <option value="">All Roles</option>
            <?php
            $r=$conn->query("SELECT * FROM employeerole");
            while($row=$r->fetch_assoc()){
                $sel=(($_GET['role_id']??'')==$row['r_id'])?'selected':'';
                echo "<option value='{$row['r_id']}' $sel>{$row['r_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<table class="table table-bordered table-sm">
<tr class="table-dark">
<th>Date</th><th>Employee</th><th>Role</th><th>Total Salary</th>
</tr>

<?php
$where="1";
if(!empty($_GET['s_from'])) $where.=" AND d.work_date>='{$_GET['s_from']}'";
if(!empty($_GET['s_to']))   $where.=" AND d.work_date<='{$_GET['s_to']}'";
if(!empty($_GET['emp_id'])) $where.=" AND d.emp_id='{$_GET['emp_id']}'";
if(!empty($_GET['role_id']))$where.=" AND d.role_id='{$_GET['role_id']}'";

$q=$conn->query("
SELECT d.work_date, e.e_name, r.r_name, SUM(d.total_salary) total
FROM dailysalary d
JOIN employee e ON e.e_id=d.emp_id
JOIN employeerole r ON r.r_id=d.role_id
WHERE $where
GROUP BY d.work_date, d.emp_id
ORDER BY d.work_date DESC
");
while($row=$q->fetch_assoc()){
    echo "<tr>
        <td>{$row['work_date']}</td>
        <td>{$row['e_name']}</td>
        <td>{$row['r_name']}</td>
        <td>{$row['total']}</td>
    </tr>";
}
?>
</table>
</div>

<!-- ================= PRODUCTION REPORT ================= -->
<div class="card p-3 mb-4">
<h5>Production Report</h5>

<form class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="p_from" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="p_to" class="form-control"></div>

    <div class="col-md-3">
        <select name="p_id" class="form-control">
            <option value="">All Products</option>
            <?php
            $p=$conn->query("SELECT * FROM product");
            while($r=$p->fetch_assoc()){
                echo "<option value='{$r['p_id']}'>{$r['p_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="emp_p" class="form-control">
            <option value="">All Employees</option>
            <?php
            $e=$conn->query("SELECT * FROM employee");
            while($r=$e->fetch_assoc()){
                echo "<option value='{$r['e_id']}'>{$r['e_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<table class="table table-bordered table-sm">
<tr class="table-dark">
<th>Date</th><th>Product</th><th>Employee</th><th>Qty</th>
</tr>

<?php
$where="1";
if(!empty($_GET['p_from'])) $where.=" AND p.p_date>='{$_GET['p_from']}'";
if(!empty($_GET['p_to']))   $where.=" AND p.p_date<='{$_GET['p_to']}'";
if(!empty($_GET['p_id']))   $where.=" AND p.p_id='{$_GET['p_id']}'";
if(!empty($_GET['emp_p']))  $where.=" AND p.e_id='{$_GET['emp_p']}'";

$q=$conn->query("
SELECT p.p_date, pr.p_name, e.e_name, p.qty
FROM production p
JOIN product pr ON pr.p_id=p.p_id
JOIN employee e ON e.e_id=p.e_id
WHERE $where
ORDER BY p.p_date DESC
");
while($row=$q->fetch_assoc()){
    echo "<tr>
        <td>{$row['p_date']}</td>
        <td>{$row['p_name']}</td>
        <td>{$row['e_name']}</td>
        <td>{$row['qty']}</td>
    </tr>";
}
?>
</table>
</div>

<!-- ================= SALES REPORT ================= -->
<div class="card p-3">
<h5>Sales Report</h5>

<form class="row g-2 mb-3">
    <div class="col-md-2"><input type="date" name="sale_from" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="sale_to" class="form-control"></div>

    <div class="col-md-3">
        <select name="sale_p" class="form-control">
            <option value="">All Products</option>
            <?php
            $p=$conn->query("SELECT * FROM product");
            while($r=$p->fetch_assoc()){
                echo "<option value='{$r['p_id']}'>{$r['p_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="pay_method" class="form-control">
            <option value="">All Payments</option>
            <option value="Cash">Cash</option>
            <option value="UPI">UPI</option>
            <option value="Card">Card</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<table class="table table-bordered table-sm">
<tr class="table-dark">
<th>Date</th><th>Product</th><th>Qty</th><th>Total</th><th>Payment</th>
</tr>

<?php
$where="1";
if(!empty($_GET['sale_from'])) $where.=" AND s.s_date>='{$_GET['sale_from']}'";
if(!empty($_GET['sale_to']))   $where.=" AND s.s_date<='{$_GET['sale_to']}'";
if(!empty($_GET['sale_p']))    $where.=" AND s.p_id='{$_GET['sale_p']}'";
if(!empty($_GET['pay_method']))$where.=" AND s.pay_method='{$_GET['pay_method']}'";

$q=$conn->query("
SELECT s.s_date, p.p_name, s.qty_sold, s.total_a, s.pay_method
FROM sales s
JOIN product p ON p.p_id=s.p_id
WHERE $where
ORDER BY s.s_date DESC
");
while($row=$q->fetch_assoc()){
    echo "<tr>
        <td>{$row['s_date']}</td>
        <td>{$row['p_name']}</td>
        <td>{$row['qty_sold']}</td>
        <td>{$row['total_a']}</td>
        <td>{$row['pay_method']}</td>
    </tr>";
}
?>
</table>
</div>

</div>
</body>
</html>
