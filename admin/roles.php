<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
    exit;
}
include "../config/db.php";

/* ADD ROLE */
if(isset($_POST['save'])){
    $r_name = $_POST['r_name'];
    $s_type = $_POST['s_type'];   // kept for record, not used in logic
    $rate   = $_POST['rate'];

    $conn->query("INSERT INTO employeerole (r_name, s_type, rate)
                  VALUES ('$r_name','$s_type','$rate')");
    header("Location: roles.php");
    exit;
}

/* DELETE ROLE */
if(isset($_GET['del'])){
    $id = $_GET['del'];
    $conn->query("DELETE FROM employeerole WHERE r_id='$id'");
    header("Location: roles.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Roles & Salary Rates</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{ box-shadow:0 4px 10px rgba(0,0,0,0.08); }
</style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Roles & Salary Rates</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">

<!-- ADD ROLE -->
<div class="card p-3 mb-4">
<h5>Add Role</h5>

<form method="post" class="row g-2">
    <div class="col-md-4">
        <input type="text" name="r_name" class="form-control"
               placeholder="Role Name (e.g. Khakhara Maker)" required>
    </div>

    <div class="col-md-3">
        <select name="s_type" class="form-control" required>
            <option value="kg">KG</option>
            <option value="roti">Roti</option>
            <option value="kg_roti">KG + Roti</option>
        </select>
    </div>

    <div class="col-md-3">
        <input type="number" step="0.01" name="rate" class="form-control"
               placeholder="Rate per KG" required>
    </div>

    <div class="col-md-2">
        <button name="save" class="btn btn-success w-100">Save</button>
    </div>
</form>
</div>

<!-- ROLE LIST -->
<div class="card p-3">
<h5>Role List</h5>

<table class="table table-bordered table-striped table-sm">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Role Name</th>
    <th>Salary Type</th>
    <th>Rate (₹ / KG)</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$q = $conn->query("SELECT * FROM employeerole ORDER BY r_id DESC");
while($row = $q->fetch_assoc()){
    echo "<tr>
        <td>{$row['r_id']}</td>
        <td>{$row['r_name']}</td>
        <td>{$row['s_type']}</td>
        <td>{$row['rate']}</td>
        <td>
            <a href='roles.php?del={$row['r_id']}'
               onclick=\"return confirm('Delete this role?')\"
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
