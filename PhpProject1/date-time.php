<?php
// ambil cust_id dari login.php
$cust_id = isset($_GET['cust_id']) ? (int)$_GET['cust_id'] : 0;

// sambung DB
$conn = mysqli_connect('127.0.0.1', 'root', '', 'dbbarber', 3307);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// bila user submit booking
if (isset($_POST['submit'])) {
    if ($cust_id <= 0) {
        echo "<script>alert('Customer ID not found. Please login again.');</script>";
    } else {
        $time  = $_POST['time'] ?? '';
        $date  = $_POST['date'] ?? '';
        $notes = mysqli_real_escape_string($conn, $_POST['notes'] ?? '');
        $name  = mysqli_real_escape_string($conn, $_POST['name'] ?? '');

        if (!empty($date)) {
            $date = date("Y-m-d", strtotime($date));
        }

        // check slot
        $checkAvailabilityQuery = "
            SELECT * FROM receipt 
            WHERE date = '$date' AND time = '$time'
            LIMIT 1
        ";
        $result = mysqli_query($conn, $checkAvailabilityQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<script>alert('Sorry, the selected time slot is already booked. Please choose another time.');</script>";
        } else {
            $insertQuery = "
                INSERT INTO receipt (cust_id, date, time, notes, name)
                VALUES ('$cust_id', '$date', '$time', '$notes', '$name')
            ";
            if (mysqli_query($conn, $insertQuery)) {
                $receiptid = mysqli_insert_id($conn);
                // lepas booking siap, masuk balik ke index untuk tengok
                echo "<script>window.location.href='index.php?receiptID=" . $receiptid . "';</script>";
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KI'AD BARBER BOOKING SYSTEM</title>
    <link rel="stylesheet" href="date-time-style.css">
    <link rel="icon" type="image/x-icon" href="favicon-32x32.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&display=swap" rel="stylesheet">
  </head>
  <body>
    <header>
      <img width="200" height="150" src="logo-removebg-preview.png">
    </header>
    <main>
      <!-- hantar balik ke file ini, tapi kekalkan cust_id dalam URL -->
      <form method="POST" action="date-time.php?cust_id=<?php echo $cust_id; ?>">
        <div class="input-group">
          <label for="date">Date:</label>
          <input type="date" id="date" name="date" required>
        </div>
        <div class="input-group">
          <label for="time">Time:</label>
          <input type="time" id="time" name="time" required>
        </div>
        <div class="input-group">
          <label for="notes">Notes (Haircut):</label>
          <textarea id="notes" name="notes" placeholder="Fade, trim, beard, etc."></textarea>
        </div>
        <div class="input-group">
          <label for="name">Name/Email:</label>
          <input type="text" id="name" name="name" placeholder="Your name or email">
        </div>
        <div class="input-group" style="display:flex; gap:10px; align-items:center;">
          <!-- butang asal -->
          <button type="submit" name="submit" value="submit">Book Now</button>

          <!-- butang baru: pergi tengok booking -->
          <a href="index.php" style="padding:8px 14px; background:#ddd; border-radius:4px; text-decoration:none; color:#000; font-size:0.9rem;">
            Past Booking
          </a>
          <!-- kalau kau nak bawa cust_id juga, boleh buat:
               <a href="index.php?cust_id=<?php echo $cust_id; ?>">Past Booking</a>
          -->
        </div>
      </form>
    </main>
  </body>
</html>
