<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
    exit;
}
include "../config/db.php";

/* ADD EMPLOYEE */
if(isset($_POST['save'])){
    $name  = $_POST['e_name'];
    $phone = $_POST['phone'];

    $conn->query("INSERT INTO employee (e_name, phone) VALUES ('$name','$phone')");
    header("Location: employees.php");
    exit;
}

/* DELETE EMPLOYEE */
if(isset($_GET['del'])){
    $id = $_GET['del'];
    $conn->query("DELETE FROM employee WHERE e_id='$id'");
    header("Location: employees.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Employees</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{ box-shadow:0 4px 10px rgba(0,0,0,0.08); }
</style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Employee Management</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">

<!-- ADD EMPLOYEE -->
<div class="card p-3 mb-4">
<h5>Add Employee</h5>

<form method="post" class="row g-2">
    <div class="col-md-5">
        <input type="text" name="e_name" class="form-control" placeholder="Employee Name" required>
    </div>
    <div class="col-md-5">
        <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
    </div>
    <div class="col-md-2">
        <button name="save" class="btn btn-success w-100">Save</button>
    </div>
</form>
</div>

<!-- EMPLOYEE LIST -->
<div class="card p-3">
<h5>Employee List</h5>

<table class="table table-bordered table-striped table-sm">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$q = $conn->query("SELECT * FROM employee ORDER BY e_id DESC");
while($row = $q->fetch_assoc()){
    echo "<tr>
        <td>{$row['e_id']}</td>
        <td>{$row['e_name']}</td>
        <td>{$row['phone']}</td>
        <td>
            <a href='employees.php?del={$row['e_id']}'
               onclick=\"return confirm('Delete this employee?')\"
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
