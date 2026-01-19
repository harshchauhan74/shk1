<?php
include "../config/db.php";

$emp_id  = $_POST['emp_id'];
$role_id = $_POST['role_id'];
$date    = $_POST['work_date'] ?? date('Y-m-d');
$kg      = $_POST['kg_done'] ?? 0;
$roti    = $_POST['roti_done'] ?? 0;
$rate    = $_POST['rate'];

$total = round(($kg * $rate) + ($roti * ($rate / 80)), 2);

$conn->query("INSERT INTO dailysalary
(emp_id, role_id, work_date, kg_done, roti_done, rate, total_salary)
VALUES
('$emp_id','$role_id','$date','$kg','$roti','$rate','$total')");

header("Location: ../admin/daily_salary.php");
exit;
