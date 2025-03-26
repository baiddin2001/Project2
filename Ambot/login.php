<?php
session_start();
include_once 'components/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_login'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']); 
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
    
    if ($select_user->rowCount() > 0) {
        setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
        header('location:home.php');
        exit();
    } else {
        echo "Invalid email or password!";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_admin_login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE email = ? AND password = ? LIMIT 1");
    $select_admin->execute([$email, $pass]);
    $row = $select_admin->fetch(PDO::FETCH_ASSOC);
    
    if ($select_admin->rowCount() > 0) {
        $_SESSION['admin_id'] = $row['id'];
        header('location:admin/admin_dashboard.php');
        exit();
    } else {
        echo "Invalid admin email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student & Admin Login</title>
    <link rel="stylesheet" href="css/login_register1.css">
    <style>
        select {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            box-sizing: border-box;
            border-radius: 10px;
            font-size: 14px;
        }

        .admin-btn {
            margin-top: 20px;
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .admin-btn:hover {
            background: white;
            color: black;
        }

        .admin-login-container {
            display: none;
        }
    </style>
</head>
<body>
<div class="container" id="container">
    <div class="form-container sign-up-container" id="signup-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h1>Create Account</h1>
            <span class="span1">Create your account and begin your academic journey today!</span>
            <input type="text" name="name" placeholder="Enter your Name" maxlength="50" required autocomplete="off">
            <input type="email" name="email" placeholder="Enter your Email" maxlength="100" required autocomplete="off">
            <select name="strand" id="strand" required>
                <option value="">Select Your Strand</option>
                <option value="ICT">ICT</option>
                <option value="HUMMS">HUMMS</option>
            </select>
            <select name="class" id="class" required>
                <option value="">Select Your Class</option>
            </select>
            <input type="password" name="pass" placeholder="Enter your Password" maxlength="20" required autocomplete="off">
            <input type="password" name="cpass" placeholder="Confirm your Password" maxlength="20" required autocomplete="off">
            <span class="profile_pic">Please select your photo <span>*</span></span>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" name="submit_signup">Sign Up</button>
        </form>
    </div>

    <!-- Student Login -->
    <div class="form-container sign-in-container" id="student-login">
        <form action="" method="post" class="login">
            <h1>Welcome!</h1>
            <span>Log in using your email and password.</span>
            <input type="email" name="email" placeholder="Enter your Email" maxlength="100" required autocomplete="off">
            <input type="password" name="pass" placeholder="Enter your Password" maxlength="20" required autocomplete="off">
            <button type="submit" name="submit_login">Login Now</button>
            <p class="link">Are you an Instructor?  
                <a href="admin/Login.php" style="color: blue;">Login here</a>
            </p>
        </form> 
    </div>

    <!-- Admin Login -->
    <div class="form-container admin-login-container" id="admin-login">
    <form action="" method="post" class="login">
        <h1>Admin Login</h1>
        <input type="email" name="email" placeholder="Admin Email" required autocomplete="off">
        <input type="password" name="pass" placeholder="Admin Password" required autocomplete="off">
        <button type="submit" name="submit_admin_login">Login Now</button>
        <p class="link">Don't have an admin account?  
            <a href="admin/admin_register.php" style="color: blue;">Register here</a>
        </p>
    </form> 
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Log In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Student!</h1>
                <p>Don't have an account yet? Sign up now!</p>
                <button class="ghost" id="signUp">Sign Up</button>
                <button class="admin-btn" id="adminLogin">Admin Login</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('signUp').addEventListener('click', () => {
        document.getElementById('container').classList.add("right-panel-active");
        document.getElementById('signup-container').style.display = 'block';
        document.getElementById('student-login').style.display = 'block';
        document.getElementById('admin-login').style.display = 'none';
    });

    document.getElementById('signIn').addEventListener('click', () => {
        document.getElementById('container').classList.remove("right-panel-active");
        document.getElementById('signup-container').style.display = 'none';
        document.getElementById('student-login').style.display = 'block';
        document.getElementById('admin-login').style.display = 'none';
    });

    document.getElementById('adminLogin').addEventListener('click', () => {
        document.getElementById('signup-container').style.display = 'none';
        document.getElementById('student-login').style.display = 'none';
        document.getElementById('admin-login').style.display = 'block';
    });
</script>

<footer class="footer1">
    &copy; <?= date('Y'); ?> by Palawan Technological College Inc. | All rights reserved!
</footer>
</body>
</html>
