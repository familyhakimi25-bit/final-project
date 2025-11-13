<?php
session_start();
include 'connection.php';   // guna fail sambungan yang betul

$is_admin = !empty($_SESSION['is_admin']);
$cust_id  = $_SESSION['cust_id'] ?? 0;

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$receipt_id = (int)$_GET['id'];

// ambil booking dulu
$sql = "SELECT * FROM receipt WHERE receipt_id = $receipt_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php?msg=Booking not found");
    exit;
}

$booking = mysqli_fetch_assoc($result);

// admin boleh delete semua
// customer hanya boleh delete booking sendiri
if ($is_admin || ($cust_id > 0 && $booking['cust_id'] == $cust_id)) {

    mysqli_query($conn, "DELETE FROM receipt WHERE receipt_id = $receipt_id");
    header("Location: index.php?msg=Deleted successfully");
    exit;

} else {
    header("Location: index.php?msg=You are not allowed to delete this booking");
    exit;
}
?>
