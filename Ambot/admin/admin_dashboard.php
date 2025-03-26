<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect to admin login if not logged in
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
    <h1>Welcome to the Admin Dashboard</h1>
    <p>This page will be updated with admin functionalities.</p>
</body>
</html>
<a href="admin_strands.php"><i class="fa-solid fa-bars-staggered"></i><span>MANAGE STRANDS</span></a>
