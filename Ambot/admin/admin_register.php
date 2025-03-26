<?php
include '../components/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_admin_register'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']); 
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Check if email already exists
    $check_admin = $conn->prepare("SELECT * FROM `admins` WHERE email = ?");
    $check_admin->execute([$email]);

    if ($check_admin->rowCount() > 0) {
        echo "Admin email already exists!";
    } else {
        // Insert admin data into the database
        $insert_admin = $conn->prepare("INSERT INTO `admins` (name, email, password) VALUES (?, ?, ?)");
        $insert_admin->execute([$name, $email, $pass]);

        if ($insert_admin) {
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
</head>
<body>
    <h2>Register Admin</h2>
    <form action="" method="post">
        <input type="text" name="name" placeholder="Enter Admin Name" required><br>
        <input type="email" name="email" placeholder="Enter Admin Email" required><br>
        <input type="password" name="pass" placeholder="Enter Password" required><br>
        <button type="submit" name="submit_admin_register">Register Admin</button>
    </form>
</body>
</html>
