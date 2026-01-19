<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
}
include "../config/db.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>Daily Salary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{box-shadow:0 4px 10px rgba(0,0,0,0.08)}
</style>
</head>

<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Daily Salary Records</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">
<div class="card p-3">

<table class="table table-bordered table-striped table-sm">
<thead class="table-dark">
<tr>
<th>Date</th>
<th>Employee</th>
<th>Role</th>
<th>KG</th>
<th>Roti</th>
<th>Rate</th>
<th>Total Salary</th>
</tr>
</thead>
<tbody>

<?php
$q=$conn->query("
SELECT d.*, e.e_name, r.r_name
FROM dailysalary d
JOIN employee e ON e.e_id=d.emp_id
JOIN employeerole r ON r.r_id=d.role_id
ORDER BY d.work_date DESC
");

$total=0;
while($row=$q->fetch_assoc()){
$total += $row['total_salary'];
echo "<tr>
<td>{$row['work_date']}</td>
<td>{$row['e_name']}</td>
<td>{$row['r_name']}</td>
<td>{$row['kg_done']}</td>
<td>{$row['roti_done']}</td>
<td>{$row['rate']}</td>
<td>{$row['total_salary']}</td>
</tr>";
}
?>
</tbody>

<tfoot class="table-secondary">
<tr>
<th colspan="6" class="text-end">Grand Total</th>
<th><?= $total ?></th>
</tr>
</tfoot>

</table>
</div>
</div>
</body>
</html>
