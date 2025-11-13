<?php
session_start();
include 'connection.php'; // pastikan nama fail ni sama dengan connection DB kau

$is_admin = !empty($_SESSION['is_admin']);
$cust_id  = $_SESSION['cust_id'] ?? 0;

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$receipt_id = (int)$_GET['id'];

// ambil data booking
$q = "SELECT * FROM receipt WHERE receipt_id = $receipt_id LIMIT 1";
$res = mysqli_query($conn, $q);

if (!$res || mysqli_num_rows($res) == 0) {
    header("Location: index.php?msg=Booking not found");
    exit;
}

$booking = mysqli_fetch_assoc($res);

// sekat customer edit booking orang lain
if (!$is_admin && $booking['cust_id'] != $cust_id) {
    header("Location: index.php?msg=You cannot edit this booking");
    exit;
}

// kalau form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $notes = mysqli_real_escape_string($conn, $_POST['notes'] ?? '');
    $date  = $_POST['date'] ?? '';
    $time  = $_POST['time'] ?? '';

    $update = "UPDATE receipt 
               SET name='$name', notes='$notes', date='$date', time='$time'
               WHERE receipt_id = $receipt_id";

    if (mysqli_query($conn, $update)) {
        header("Location: index.php?msg=Updated successfully");
        exit;
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container" style="max-width: 600px;">
    <h3 class="mb-3">Edit Booking</h3>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Name/Email</label>
            <input type="text" name="name" class="form-control" value="<?php echo $booking['name']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Notes (Haircut)</label>
            <textarea name="notes" class="form-control"><?php echo $booking['notes']; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="<?php echo $booking['date']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Time</label>
            <input type="time" name="time" class="form-control" value="<?php echo $booking['time']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
