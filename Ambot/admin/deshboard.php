<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location:../admin_login.php'); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to Admin Dashboard</h1>
    <a href="logout.php">Logout</a>
</body>
</html>
