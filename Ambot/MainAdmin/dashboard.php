<?php
session_start();
include_once '../components/connect.php';

if (!isset($_SESSION['mainadmin_id'])) {
    header('location:../login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_strand'])) {
    $strand_name = filter_var($_POST['strand_name'], FILTER_SANITIZE_STRING);
    
    if (!empty($strand_name)) {
        $insert_strand = $conn->prepare("INSERT INTO `strands` (name) VALUES (?)");
        $insert_strand->execute([$strand_name]);
        echo "Strand successfully created!";
    } else {
        echo "Please enter a strand name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <h2>Main Admin Dashboard</h2>
        <form method="post">
            <input type="text" name="strand_name" placeholder="Enter Strand Name" required>
            <button type="submit" name="create_strand">Create Strand</button>
        </form>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
