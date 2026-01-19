<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../index.php");
    exit;
}
include "../config/db.php";

/* ADD SALE */
if(isset($_POST['save'])){
    $p_id       = $_POST['p_id'];
    $s_date     = $_POST['s_date'];
    $qty_sold   = $_POST['qty_sold'];
    $pay_method = $_POST['pay_method'];

    // get product cost
    $p = $conn->query("SELECT cost FROM product WHERE p_id='$p_id'")->fetch_assoc();
    $cost = $p['cost'];

    // total amount (ROUND FIGURE – 2 decimals)
    $total_amt = round($qty_sold * $cost, 2);

    $conn->query("INSERT INTO sales
        (p_id, s_date, qty_sold, total_a, pay_method)
        VALUES
        ('$p_id','$s_date','$qty_sold','$total_amt','$pay_method')
    ");

    header("Location: sales.php");
    exit;
}
/* EDIT MODE */
$edit_sale = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit_sale = $conn->query("SELECT * FROM sales WHERE s_id='$id'")->fetch_assoc();
}

/* UPDATE PAYMENT METHOD */ 
if(isset($_POST['update_payment'])){
    $s_id       = $_POST['s_id'];
    $pay_method = $_POST['pay_method'];

    $conn->query("UPDATE sales SET pay_method='$pay_method' WHERE s_id='$s_id'");

    header("Location: sales.php");
    exit;
}   
/* DELETE SALE */
if(isset($_GET['del'])){
    $id = $_GET['del'];
    $conn->query("DELETE FROM sales WHERE s_id='$id'");
    header("Location: sales.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sales Entry</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script>
function calcTotal(){
    let price = parseFloat(document.getElementById("price").value || 0);
    let qty   = parseFloat(document.getElementById("qty").value || 0);
    let total = price * qty;
    document.getElementById("total").value = total.toFixed(2); // ROUND
}
</script>

<style>
.card{ box-shadow:0 4px 10px rgba(0,0,0,0.08); }
</style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Sales Entry</span>
    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back</a>
</nav>

<div class="container my-4">

<!-- ADD SALE -->
<div class="card p-3 mb-4">
<h5>Add Sale</h5>

<form method="post" class="row g-2">

<div class="col-md-3">
    <label>Product</label>
    <select name="p_id" class="form-control"
    onchange="document.getElementById('price').value=this.selectedOptions[0].dataset.cost; calcTotal();"
    required>
        <option value="">-- Select Product --</option>
        <?php
        $q = $conn->query("SELECT * FROM product ORDER BY p_name");
        while($r=$q->fetch_assoc()){
            echo "<option value='{$r['p_id']}' data-cost='{$r['cost']}'>
                    {$r['p_name']}
                  </option>";
        }
        ?>
    </select>
</div>

<div class="col-md-2">
    <label>Date</label>
    <input type="date" name="s_date" class="form-control"
       value="<?= date('Y-m-d') ?>" required>

</div>

<div class="col-md-2">
    <label>Qty Sold</label>
    <input type="number" step="0.01" id="qty" name="qty_sold"
           onkeyup="calcTotal()" class="form-control" required>
</div>

<div class="col-md-2">
    <label>Price</label>
    <input type="text" id="price" class="form-control" readonly>
</div>

<div class="col-md-2">
    <label>Total</label>
    <input type="text" id="total" class="form-control" readonly>
</div>

<div class="col-md-1">
    <label>&nbsp;</label>
    <button name="save" class="btn btn-success w-100">Save</button>
</div>

<div class="col-md-2">
    <label>Payment</label>
    <select name="pay_method" class="form-control" required>
        <option value="Cash">Cash</option>
        <option value="UPI">UPI</option>
        <option value="Card">Card</option>
    </select>
</div>

</form>
</div>
<?php if($edit_sale){ ?>
<div class="card p-3 mb-4">
<h5>Edit Payment Method</h5>

<form method="post" class="row g-2">
    <input type="hidden" name="s_id" value="<?= $edit_sale['s_id'] ?>">

    <div class="col-md-4">
        <label>Payment Method</label>
        <select name="pay_method" class="form-control" required>
            <option value="Cash" <?= $edit_sale['pay_method']=='Cash'?'selected':'' ?>>Cash</option>
            <option value="UPI" <?= $edit_sale['pay_method']=='UPI'?'selected':'' ?>>UPI</option>
            <option value="Card" <?= $edit_sale['pay_method']=='Card'?'selected':'' ?>>Card</option>
        </select>
    </div>

    <div class="col-md-2 align-self-end">
        <button name="update_payment" class="btn btn-success w-100">Update</button>
    </div>

    <div class="col-md-2 align-self-end">
        <a href="sales.php" class="btn btn-secondary w-100">Cancel</a>
    </div>
</form>
</div>
<?php } ?>


<!-- SALES LIST -->
<div class="card p-3">
<h5>Sales Records</h5>

<table class="table table-bordered table-striped table-sm">
<thead class="table-dark">
<tr>
    <th>Date</th>
    <th>Product</th>
    <th>Qty</th>
    <th>Total Amount</th>
    <th>Payment</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$q = $conn->query("
    SELECT s.*, p.p_name
    FROM sales s
    JOIN product p ON p.p_id=s.p_id
    ORDER BY s.s_date DESC
");

while($row=$q->fetch_assoc()){
    echo "<tr>
        <td>{$row['s_date']}</td>
        <td>{$row['p_name']}</td>
        <td>{$row['qty_sold']}</td>
        <td>{$row['total_a']}</td>
        <td>{$row['pay_method']}</td>
        <td>
            <a href='sales.php?del={$row['s_id']}'
               onclick=\"return confirm('Delete this sale?')\"
               class='btn btn-sm btn-danger'>Delete</a>
            <a href='sales.php?edit={$row['s_id']}'
               class='btn btn-sm btn-primary'>Edit</a>
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
