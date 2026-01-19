<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
    exit;
}
include "../config/db.php";

/* ADD PRODUCTION */
if(isset($_POST['save'])){
    $p_date = $_POST['p_date'];
    $p_id   = $_POST['p_id'];
    $e_id   = $_POST['e_id'];
    $r_id   = $_POST['r_id'];
    $qty    = $_POST['qty'];

    $conn->query("INSERT INTO production
        (p_date, p_id, e_id, r_id, qty)
        VALUES
        ('$p_date','$p_id','$e_id','$r_id','$qty')
    ");
    header("Location: production.php");
    exit;
}

/* DELETE PRODUCTION */
if(isset($_GET['del'])){
    $id = $_GET['del'];
    $conn->query("DELETE FROM production WHERE pr_id='$id'");
    header("Location: production.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Production Entry</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{ box-shadow:0 4px 10px rgba(0,0,0,0.08); }
</style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Production Entry</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">

<!-- ADD PRODUCTION -->
<div class="card p-3 mb-4">
<h5>Add Production</h5>

<form method="post" class="row g-2">

<div class="col-md-2">
    <label>Date</label>
    <input type="date" name="p_date" class="form-control"
       value="<?= date('Y-m-d') ?>" required>

</div>

<div class="col-md-3">
    <label>Product</label>
    <select name="p_id" class="form-control" required>
        <option value="">-- Select Product --</option>
        <?php
        $q = $conn->query("SELECT * FROM product ORDER BY p_name");
        while($r=$q->fetch_assoc()){
            echo "<option value='{$r['p_id']}'>{$r['p_name']}</option>";
        }
        ?>
    </select>
</div>

<div class="col-md-3">
    <label>Employee</label>
    <select name="e_id" class="form-control" required>
        <option value="">-- Select Employee --</option>
        <?php
        $q = $conn->query("SELECT * FROM employee ORDER BY e_name");
        while($r=$q->fetch_assoc()){
            echo "<option value='{$r['e_id']}'>{$r['e_name']}</option>";
        }
        ?>
    </select>
</div>

<div class="col-md-2">
    <label>Role</label>
    <select name="r_id" class="form-control" required>
        <option value="">-- Select Role --</option>
        <?php
        $q = $conn->query("SELECT * FROM employeerole ORDER BY r_name");
        while($r=$q->fetch_assoc()){
            echo "<option value='{$r['r_id']}'>{$r['r_name']}</option>";
        }
        ?>
    </select>
</div>

<div class="col-md-2">
    <label>Quantity</label>
    <input type="number" step="0.01" name="qty" class="form-control" required>
</div>

<div class="col-md-12 mt-2">
    <button name="save" class="btn btn-success w-100">Save Production</button>
</div>

</form>
</div>

<!-- PRODUCTION LIST -->
<div class="card p-3">
<h5>Production Records</h5>

<table class="table table-bordered table-striped table-sm">
<thead class="table-dark">
<tr>
    <th>Date</th>
    <th>Product</th>
    <th>Employee</th>
    <th>Role</th>
    <th>Quantity</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$q = $conn->query("
    SELECT p.*, pr.p_name, e.e_name, r.r_name
    FROM production p
    JOIN product pr ON pr.p_id = p.p_id
    JOIN employee e ON e.e_id = p.e_id
    JOIN employeerole r ON r.r_id = p.r_id
    ORDER BY p.p_date DESC
");

while($row = $q->fetch_assoc()){
    echo "<tr>
        <td>{$row['p_date']}</td>
        <td>{$row['p_name']}</td>
        <td>{$row['e_name']}</td>
        <td>{$row['r_name']}</td>
        <td>{$row['qty']}</td>
        <td>
            <a href='production.php?del={$row['pr_id']}'
               onclick=\"return confirm('Delete this production record?')\"
               class='btn btn-sm btn-danger'>Delete</a>
        </td>
    </tr>";
}
?>
</tbody>
</table>
</div>

</div>
</body>
</html>
