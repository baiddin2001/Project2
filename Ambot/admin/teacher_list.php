<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}

// Handle changing tutor status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $tutor_id = $_POST['tutor_id'];
    $new_status = $_POST['status'];
    
    $update_status = $conn->prepare("UPDATE tutors SET status = ? WHERE id = ?");
    $update_status->execute([$new_status, $tutor_id]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh page after update
    exit();
}

// Handle deleting a tutor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_tutor'])) {
    $tutor_id = $_POST['tutor_id'];
    
    $delete_tutor = $conn->prepare("DELETE FROM tutors WHERE id = ?");
    $delete_tutor->execute([$tutor_id]);
    
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh page after deletion
    exit();
}

// Fetch all tutors
$fetch_tutors = $conn->prepare("SELECT * FROM tutors");
$fetch_tutors->execute();
$tutors = $fetch_tutors->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tutors</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<style>

    .update-btn {
    background-color: #007bff; /* Blue color */
    color: white;
    border: none;
    padding: 8px 12px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background 0.3s ease-in-out;
}

.update-btn:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

.header {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            padding: 15px 0;
            background-color: #f4f4f4;
            width: 100%;
            position: fixed;
            top: 0;
            left: 4cm;
            z-index: 1000;
        }

        .back-button {
         display: block !important; 
         position: fixed;
         top: 10px; 
         right: 20px;
         background-color: #66bb6a;
         color: black;
         font-weight: 500;
         padding: 8px 25px;
         border: 2px solid #ddd;
         border-radius: 5px;
         text-decoration: none;
         font-size: 18px;
         transition: background-color 0.3s ease;
         z-index: 2000;
      }
      .back-button:hover {
         background-color: #e0e0e0;
      }

</style>
<body>
<header class="header">
    <span class="logo">PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
</header>
<a href="javascript:history.back()" class="back-button">
   <i class="fas fa-arrow-left"></i> Back
</a>

<div class="side-bar">
    <nav class="navbar">
        <a href="admin_strands.php">
            <i class="fas fa-layer-group"></i><span>Manage Strands</span>
        </a>
        <a href="admin_subjects.php">
            <i class="fas fa-book"></i><span>Subjects</span>
        </a>
        <a href="student_teacher.php">
            <i class="fas fa-users"></i><span>Students/Tutors</span>
        </a>
        <a href="../admin_login.php" onclick="return confirm('Logout from this website?');">
            <i class="fas fa-right-from-bracket"></i><span>LOGOUT</span>
        </a>
    </nav>
</div>

<section class="manage-tutors">
    <h1>Manage Teachers</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Update Status</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tutors as $tutor) { ?>
                <tr>
                    <td><?= htmlspecialchars($tutor['name']); ?></td>
                    <td><?= htmlspecialchars($tutor['email']); ?></td>
                    <td><?= htmlspecialchars($tutor['status']); ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="tutor_id" value="<?= $tutor['id']; ?>">
                            <select name="status">
                                <option value="Active" <?= $tutor['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                <option value="Inactive" <?= $tutor['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                            <button type="submit" name="update_status" class="update-btn">Update</button>
                        </form>
                    </td>
                    <td>
                        <a href="admin_edit_teacher.php?id=<?= $tutor['id']; ?>" class="update-btn">Edit</a>
                    </td>
                    <td>
                        <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this tutor?');">
                            <input type="hidden" name="tutor_id" value="<?= $tutor['id']; ?>">
                            <button type="submit" name="delete_tutor" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .manage-tutors {
        width: 80%;
        max-width: 900px;
        margin: 2cm auto;
        margin-left: 9.5cm; /* Adjusts the left margin */
        text-align: center;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 { font-size: 28px; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; font-size: 18px; }
    th, td { padding: 14px; border: 1px solid #ddd; }
    th { background-color: #f4f4f4; font-size: 20px; }
    .delete-btn { background-color: red; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 5px; }
</style>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>