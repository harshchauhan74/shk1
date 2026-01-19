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
<title>Daily Work Entry</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script>
function calcSalary(){
    let kg   = parseFloat(document.getElementById("kg").value || 0);
    let roti = parseFloat(document.getElementById("roti").value || 0);
    let rate = parseFloat(document.getElementById("rate").value || 0);

    let total = (kg * rate) + (roti * (rate / 80));
    document.getElementById("total").value = total.toFixed(4);
}
</script>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Daily Work & Salary Entry</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">

<div class="card p-4">

<form method="post" action="../api/save_salary.php">

<!-- EMPLOYEE -->
<div class="mb-3">
<label>Employee</label>
<select name="emp_id" class="form-control" required>
<option value="">-- Select Employee --</option>
<?php
$emp = $conn->query("SELECT * FROM employee ORDER BY e_name");
while($e = $emp->fetch_assoc()){
    echo "<option value='{$e['e_id']}'>{$e['e_name']}</option>";
}
?>
</select>
</div>

<!-- ROLE -->
<div class="mb-3">
<label>Role</label>
<select name="role_id" class="form-control"
onchange="document.getElementById('rate').value=this.selectedOptions[0].dataset.rate; calcSalary();"
required>
<option value="">-- Select Role --</option>
<?php
$role = $conn->query("SELECT * FROM employeerole ORDER BY r_name");
while($r = $role->fetch_assoc()){
    echo "<option value='{$r['r_id']}' data-rate='{$r['rate']}'>
            {$r['r_name']} (₹{$r['rate']}/kg)
          </option>";
}
?>
</select>
</div>

<!-- DATE -->
<div class="mb-3">
<label>Work Date</label>
<input type="date" name="s_date" class="form-control"
       value="<?= date('Y-m-d') ?>" required>

</div>

<!-- KG -->
<div class="mb-3">
<label>KG Done</label>
<input type="number" step="0.01" id="kg" name="kg_done"
onkeyup="calcSalary()" class="form-control" placeholder="Enter KG">
</div>

<!-- ROTI -->
<div class="mb-3">
<label>Roti Done</label>
<input type="number" step="0.01" id="roti" name="roti_done"
onkeyup="calcSalary()" class="form-control" placeholder="Enter Rotis">
</div>

<!-- RATE -->
<div class="mb-3">
<label>Rate (₹ per KG)</label>
<input type="text" id="rate" name="rate" class="form-control" readonly>
</div>

<!-- TOTAL -->
<div class="mb-3">
<label>Total Salary (Auto Calculated)</label>
<input type="text" id="total" name="total_salary" class="form-control" readonly>
</div>

<button class="btn btn-success w-100">Save Salary</button>

</form>

</div>
</div>

</body>
</html>
