<?php
session_start();
include_once 'components/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_admin_login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Correct email sanitization

    // Fetch admin details using email
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE email = ? LIMIT 1");
    $select_admin->execute([$email]);
    $row = $select_admin->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($_POST['pass'], $row['password'])) { 
        // Password matches
        $_SESSION['admin_id'] = $row['id'];
        header('location:admin/admin_dashboard.php');
        exit();
    } else {
        echo "<script>alert('Invalid admin email or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/login_register1.css">
    <style>
        .sign-in-container_admin {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 400px;
            height: 500px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="sign-in-container_admin" id="admin-login">
        <form action="" method="post" class="login">
            <h1>Welcome Admin!</h1>
            <span>Log in using your email and password.</span>
            <input type="email" name="email" placeholder="Enter your Admin Email" maxlength="100" required autocomplete="off">
            <input type="password" name="pass" placeholder="Enter your Password" maxlength="100" required autocomplete="off">
            <button type="submit" name="submit_admin_login">Login Now</button>
            <p class="link">Don't have an admin account?  
                <a href="admin_register.php" style="color: blue;">Register here</a>
            </p>
        </form> 
    </div>

    <footer class="footer1">
        &copy; <?= date('Y'); ?> by Palawan Technological College Inc. | All rights reserved!
    </footer>
</body>
</html>
