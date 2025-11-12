<?php
session_start();
include 'connection.php'; // sambungan DB kau yang guna port 3307

// detect role
$is_admin = !empty($_SESSION['is_admin']);     // true kalau admin
$cust_id  = $_SESSION['cust_id'] ?? 0;         // id customer kalau user biasa

// kalau bukan admin dan tak ada cust_id langsung -> paksa login customer
if (!$is_admin && $cust_id == 0) {
    header("Location: login.php");
    exit;
}

// SQL ikut role
if ($is_admin) {
    // admin tengok semua
    $sql = "SELECT * FROM receipt ORDER BY date DESC, time DESC";
} else {
    // customer tengok booking dia saja
    $sql = "SELECT * FROM receipt WHERE cust_id = '$cust_id' ORDER BY date DESC, time DESC";
}
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
     crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>KI'AD BARBERSHOP SYSTEM</title>
</head>
<body>

<nav class="navbar navbar-light justify-content-between px-4 fs-5 mb-5"
style="background-color: #9DB2BF">
    <span>KI'AD BARBERSHOP SYSTEM</span>
    <span>
        <i class="fa-solid fa-user-tie me-2"></i>
        <?php
          if ($is_admin) {
            echo $_SESSION['admin_email'] ?? 'Admin';
          } else {
            echo $_SESSION['cust_name'] ?? 'Customer';
          }
        ?>
        <a href="logout.php" class="btn btn-sm btn-outline-dark ms-3">Logout</a>
    </span>
</nav>

<div class="container">

    <?php if ($is_admin): ?>
        <!-- admin -->
        <a href="add_new.php" class="btn btn-dark mb-3">Add new</a>
    <?php else: ?>
        <!-- customer -->
        <a href="date-time.php?cust_id=<?php echo $cust_id; ?>" class="btn btn-primary mb-3">
            Make a booking
        </a>
    <?php endif; ?>

    <table class="table table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th scope="col">Receipt ID</th>
          <th scope="col">Name</th>
          <th scope="col">Note (Haircut)</th>
          <th scope="col">Date</th>
          <th scope="col">Time</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td><?php echo $row['receipt_id']; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['notes']; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo $row['time']; ?></td>
          <td>
            <!-- semua orang (admin & customer) boleh edit/delete booking yang dipaparkan -->
            <!-- sebab customer kita memang query booking dia saja -->
            <a href="edit.php?id=<?php echo $row['receipt_id']; ?>" class="link-dark">
                <i class="fa-solid fa-pen-to-square fs-5 me-3"></i>
            </a>
            <a href="delete.php?id=<?php echo $row['receipt_id']; ?>" class="link-dark"
               onclick="return confirm('Delete this booking?');">
                <i class="fa-solid fa-trash fs-5 text-danger"></i>
            </a>
          </td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='6'>No booking found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
