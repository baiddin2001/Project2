<?php
include 'components/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_admin_register'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Secure hashing

    // Check if email already exists
    $check_admin = $conn->prepare("SELECT * FROM `admins` WHERE email = ?");
    $check_admin->execute([$email]);

    if ($check_admin->rowCount() > 0) {
        echo "Admin email already exists!";
    } else {
        // Insert admin data into the database
        $insert_admin = $conn->prepare("INSERT INTO `admins` (name, email, password) VALUES (?, ?, ?)");
        $insert_admin->execute([$name, $email, $pass]);

        if ($insert_admin->rowCount() > 0) {
            echo "Admin account created successfully!";
        } else {
            echo "Failed to create admin account.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="css/login_register1.css">
</head>

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
        z-index: 2; /* Fixed */
    }
</style>

<body>
<div class="sign-in-container_admin">
    <form action="" method="post">
        <h1>Register Admin</h1>
        <input type="text" name="name" placeholder="Enter Admin Name" required>
        <input type="email" name="email" placeholder="Enter Admin Email" required>
        <input type="password" name="pass" placeholder="Enter Password" required>
        <button type="submit" name="submit_admin_register">Register Admin</button>
        <br>
        <button class="admin-btn" onclick="location.href='admin_login.php'">Admin Login</button>
    </form>
</div>

</body>
</html>
