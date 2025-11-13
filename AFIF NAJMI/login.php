<?php
session_start(); // penting supaya kita boleh simpan cust_id dalam session

// sambung database
$conn = mysqli_connect('127.0.0.1', 'root', '', 'dbbarber', 3307);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SIGN UP
if (isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email    = $_POST['email'] ?? '';

    if ($username !== '' && $password !== '') {

        $sql = "INSERT INTO customer (username, password, email)
                VALUES ('$username', '$password', '$email')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Account created! You can login now');</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Please fill in all required fields');</script>";
    }
}

// LOGIN
if (isset($_POST['login'])) {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email !== '' && $password !== '') {
        $sql = "SELECT * FROM customer WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data['password'] === $password) {
                // SIMPAN DALAM SESSION ➜ supaya index.php boleh kenal customer
                $_SESSION['cust_id']   = $user_data['cust_id'];
                $_SESSION['cust_name'] = $user_data['username'];
                $_SESSION['cust_email'] = $user_data['email'];

                // pergi ke page booking
                header("Location: date-time.php?cust_id=" . $user_data['cust_id']);
                exit;
            }
        }
        echo "<script>alert('Wrong Email or Password!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Login-style.css">
  <title>KI'AD BARBER BOOKING SYSTEM</title>
  <link rel="icon" type="x-icon" href="favicon-32x32.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <img width="200" height="150" src="logo-removebg-preview.png" sizes="(max-width: 200px) 100vw, 150px">
  </header>

  <div class="container" id="container">
    <!-- SIGN UP FORM -->
    <div class="form-container sign-up-container">
      <form action="login.php" method="POST">
        <h1>Create Account</h1>
        <div class="social-container">
          <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
          <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <span>or use your email for registration</span>
        <input type="text" name="username" placeholder="Name" />
        <input type="email" name="email" placeholder="Email" />
        <input type="password" name="password" placeholder="Password" />
        <button type="submit" name="register" value="1">Sign Up</button>
      </form>
    </div>

    <!-- LOGIN FORM -->
    <div class="form-container sign-in-container">
      <form action="login.php" method="POST">
        <h1>Log in</h1>
        <div class="social-container">
          <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
          <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <span>or use your account</span>
        <input type="email" name="email" placeholder="Email" />
        <input type="password" name="password" placeholder="Password" />
        <a href="#">Forgot your password?</a>
        <button type="submit" name="login" value="submit">Log in</button>
        <!-- link admin -->
        <a class="L" href="index_1.php">admin</a>
      </form>
    </div>

    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Welcome Back!</h1>
          <p>To keep connected with us please login with your personal info</p>
          <button class="ghost" id="signIn">Log In</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Hello, Gentlemen!</h1>
          <p>Enter your personal details and start journey with us</p>
          <button class="ghost" id="signUp">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <section class="footer">
    <div class="social">
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-whatsapp"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
    </div>

    <ul class="list">
      <li><a href="#">Terms</a></li>
      <li><a href="#">Privacy Policy</a></li>
    </ul>

    <p class="copyright">
      Future Coders @ 2023
    </p>
  </section>

  <script src="Login-script.js"></script>
</body>
</html>
