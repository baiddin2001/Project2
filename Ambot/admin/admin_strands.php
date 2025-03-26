<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}

// Handle Adding a Strand
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_strand'])) {
    $strand_name = filter_var($_POST['strand_name'], FILTER_SANITIZE_STRING);

    $check_strand = $conn->prepare("SELECT * FROM `strands` WHERE name = ?");
    $check_strand->execute([$strand_name]);

    if ($check_strand->rowCount() > 0) {
        $message = "Strand already exists!";
    } else {
        $insert_strand = $conn->prepare("INSERT INTO `strands` (name) VALUES (?)");
        $insert_strand->execute([$strand_name]);

        if ($insert_strand) {
            $message = "Strand added successfully!";
        } else {
            $message = "Failed to add strand.";
        }
    }
}

// Handle Deleting a Strand
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_strand = $conn->prepare("DELETE FROM `strands` WHERE id = ?");
    $delete_strand->execute([$delete_id]);

    header("Location: admin_strands.php");
    exit();
}

// Fetch all strands
$fetch_strands = $conn->prepare("SELECT * FROM `strands`");
$fetch_strands->execute();
$strands = $fetch_strands->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Strands</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="manage-strands">
    <h1>Manage Strands</h1>

    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

    <form action="" method="post">
        <input type="text" name="strand_name" placeholder="Enter Strand Name" required>
        <button type="submit" name="add_strand">Add Strand</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Strand Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($strands as $strand) { ?>
                <tr>
                    <td><?= htmlspecialchars($strand['name']); ?></td>
                    <td>
                        <a href="admin_strands.php?delete=<?= $strand['id']; ?>" onclick="return confirm('Delete this strand?');" class="delete-btn">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
    }
    .manage-strands {
        width: 50%;
        margin: auto;
        text-align: center;
    }
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f4f4f4;
    }
    .message {
        color: green;
    }
    .delete-btn {
        color: red;
        text-decoration: none;
    }
</style>

<script src="../js/admin_script.js"></script>

</body>
</html>
