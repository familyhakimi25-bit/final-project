<?php
session_start();

// username & password yang dibenarkan
$admin_user = 'hakimi';
$admin_pass = '123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = $_POST['uname'] ?? '';
    $pass  = $_POST['password'] ?? '';

    if ($uname === $admin_user && $pass === $admin_pass) {
        // set session untuk index.php guna
        $_SESSION['is_admin']   = true;
        $_SESSION['admin_email'] = $uname; // kau guna ni dalam navbar

        // pergi ke admin dashboard
        header("Location: index.php");
        exit;
    } else {
        // kalau salah, patah balik ke form login dengan error
        header("Location: login_1.php?error=Wrong username or password");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>ADMIN LOGIN</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
     <form action="login_1.php" method="post">
     	<h2>ADMIN LOGIN</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
     	<?php } ?>
     	<label>Username</label>
     	<input type="text" name="uname" placeholder="Username" required>

     	<label>Password</label>
     	<input type="password" name="password" placeholder="Password" required>

     	<button type="submit">Login</button>
     </form>
</body>
</html>
