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
        $message = "Strand added successfully!";
    }
}

// Handle Deleting a Strand
if (isset($_GET['delete'])) {
    $strand_id = $_GET['delete'];
    
    // Delete associated assignments first to maintain integrity
    $delete_assignments = $conn->prepare("DELETE FROM `strand_assignments` WHERE strand_id = ?");
    $delete_assignments->execute([$strand_id]);

    // Delete the strand
    $delete_strand = $conn->prepare("DELETE FROM `strands` WHERE id = ?");
    $delete_strand->execute([$strand_id]);
    
    header('Location: admin_strands.php'); // Refresh page after deletion
    exit();
}

// Handle Assigning a Teacher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_teacher'])) {
    $strand_id = $_POST['strand_id'];
    $teacher_id = $_POST['teacher_id'];

    // Remove existing assignment if any
    $remove_existing = $conn->prepare("DELETE FROM `strand_assignments` WHERE strand_id = ?");
    $remove_existing->execute([$strand_id]);

    // Assign teacher to the strand
    $assign_teacher = $conn->prepare("INSERT INTO `strand_assignments` (strand_id, teacher_id) VALUES (?, ?)");
    $assign_teacher->execute([$strand_id, $teacher_id]);

    $message = "Teacher assigned successfully!";
}

// Fetch strands with assigned teachers
$fetch_strands = $conn->prepare("SELECT strands.*, tutors.name AS tutor_name, tutors.id AS tutor_id FROM `strands`
    LEFT JOIN `strand_assignments` ON strands.id = strand_assignments.strand_id
    LEFT JOIN `tutors` ON strand_assignments.teacher_id = tutors.id");
$fetch_strands->execute();
$strands = $fetch_strands->fetchAll(PDO::FETCH_ASSOC);

// Fetch all teachers
$fetch_teachers = $conn->prepare("SELECT * FROM `tutors`");
$fetch_teachers->execute();
$teachers = $fetch_teachers->fetchAll(PDO::FETCH_ASSOC);
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
<header class="header">
    <span class="logo">PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
</header>

<div class="side-bar">
    <nav class="navbar">
        <a href="admin_strands.php">
            <i class="fas fa-layer-group"></i><span>Manage Strands</span>
        </a>
        <a href="admin_subjects.php">
            <i class="fas fa-book"></i><span>Subjects</span>
        </a>
        <a href="student_teacher.php">
            <i class="fas fa-users"></i><span>Students/Teachers</span>
        </a>
        <a href="../admin_login.php" onclick="return confirm('Logout from this website?');">
            <i class="fas fa-right-from-bracket"></i><span>LOGOUT</span>
        </a>
    </nav>
</div>

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
                <th>Teacher</th>
                <th>Assign Teacher</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($strands as $strand) { ?>
                <tr>
                    <td><?= htmlspecialchars($strand['name']); ?></td>
                    <td><?= htmlspecialchars($strand['tutor_name'] ?? 'Unassigned'); ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="strand_id" value="<?= $strand['id']; ?>">
                            <select name="teacher_id" required>
                                <option value="">Select Teacher</option>
                                <?php foreach ($teachers as $teacher) { ?>
                                    <option value="<?= $teacher['id']; ?>" <?= ($strand['tutor_id'] == $teacher['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($teacher['name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <button type="submit" name="assign_teacher">Assign</button>
                        </form>
                    </td>
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
    body { font-family: Arial, sans-serif; padding: 20px; }
    .manage-strands { width: 80%; max-width: 900px; margin: auto; text-align: center; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
    h1 { font-size: 28px; }
    .message { color: green; font-weight: bold; font-size: 18px; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; font-size: 18px; }
    th, td { padding: 14px; border: 1px solid #ddd; }
    th { background-color: #f4f4f4; font-size: 20px; }
    .delete-btn { color: white; text-decoration: none; background: red; padding: 8px 12px; font-size: 16px; border-radius: 5px; }
    .delete-btn:hover { background: darkred; }
</style>

    <style>
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
            left: 0;
            z-index: 1000;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .manage-strands {
            width: 80%;
            max-width: 900px;
            margin: 80px auto 20px; /* Increased top margin to 80px */
            text-align: center;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        
        h1 {
            font-size: 28px;
        }

        .back-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        form input, form button {
            font-size: 18px;
            padding: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 18px;
        }
        
        th, td {
            padding: 14px;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #f4f4f4;
            font-size: 20px;
        }
        
        .message {
            color: green;
            font-weight: bold;
            font-size: 18px;
        }
        
        .delete-btn {
            color: white;
            text-decoration: none;
            background: red;
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 5px;
        }
        
        .delete-btn:hover {
            background: darkred;
        }
    </style>

    <script src="../js/admin_script.js"></script>

    </body>
    </html>
