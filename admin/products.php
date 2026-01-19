<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
    exit;
}
include "../config/db.php";

/* ADD PRODUCT */
if(isset($_POST['save'])){
    $p_name = $_POST['p_name'];
    $cost   = $_POST['cost'];
    $unit   = $_POST['unit'];

    $conn->query("INSERT INTO product (p_name, cost, unit)
                  VALUES ('$p_name','$cost','$unit')");
    header("Location: products.php");
    exit;
}

/* DELETE PRODUCT */
if(isset($_GET['del'])){
    $id = $_GET['del'];
    $conn->query("DELETE FROM product WHERE p_id='$id'");
    header("Location: products.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Products</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}
</style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Product Management</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">

<!-- ADD PRODUCT -->
<div class="card p-3 mb-4">
<h5>Add Product</h5>

<form method="post" class="row g-2">
    <div class="col-md-4">
        <input type="text" name="p_name" class="form-control"
               placeholder="Product Name (e.g. Wheat Khakhara)" required>
    </div>

    <div class="col-md-3">
        <input type="number" step="0.01" name="cost" class="form-control"
               placeholder="Cost" required>
    </div>

    <div class="col-md-3">
        <input type="text" name="unit" class="form-control"
               placeholder="Unit (pcs / kg / packet)" required>
    </div>

    <div class="col-md-2">
        <button name="save" class="btn btn-success w-100">Save</button>
    </div>
</form>
</div>

<!-- PRODUCT LIST -->
<div class="card p-3">
<h5>Product List</h5>

<table class="table table-bordered table-striped table-sm">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Product Name</th>
    <th>Cost</th>
    <th>Unit</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$q = $conn->query("SELECT * FROM product ORDER BY p_id DESC");
while($row = $q->fetch_assoc()){
    echo "<tr>
        <td>{$row['p_id']}</td>
        <td>{$row['p_name']}</td>
        <td>{$row['cost']}</td>
        <td>{$row['unit']}</td>
        <td>
            <a href='product.php?del={$row['p_id']}'
               onclick=\"return confirm('Delete this product?')\"
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
