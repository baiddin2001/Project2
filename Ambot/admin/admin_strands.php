<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle Add Strand
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

// Handle Delete Strand
if (isset($_GET['delete'])) {
    $strand_id = $_GET['delete'];
    $conn->prepare("DELETE FROM `strand_assignments` WHERE strand_id = ?")->execute([$strand_id]);
    $conn->prepare("DELETE FROM `strands` WHERE id = ?")->execute([$strand_id]);
    header('Location: admin_strands.php');
    exit();
}

// Handle Assign Teacher (one teacher per strand)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_teacher'])) {
    $strand_id = $_POST['strand_id'];
    $teacher_id = $_POST['teacher_id'];

    // Remove any previous assignments for this strand
    $conn->prepare("DELETE FROM `strand_assignments` WHERE strand_id = ?")->execute([$strand_id]);

    // Insert the new assignment
    $conn->prepare("INSERT INTO `strand_assignments` (strand_id, teacher_id) VALUES (?, ?)")->execute([$strand_id, $teacher_id]);

    $message = "Teacher assigned successfully.";
}

// Fetch strands
$fetch_strands = $conn->query("SELECT * FROM strands");
$strands = $fetch_strands->fetchAll(PDO::FETCH_ASSOC);

// Fetch tutors
$fetch_teachers = $conn->query("SELECT * FROM tutors");
$teachers = $fetch_teachers->fetchAll(PDO::FETCH_ASSOC);

// Get assigned teacher per strand
$strand_assignments = [];
foreach ($strands as $strand) {
    $stmt = $conn->prepare("
        SELECT tutors.id, tutors.name 
        FROM strand_assignments 
        JOIN tutors ON strand_assignments.teacher_id = tutors.id 
        WHERE strand_assignments.strand_id = ?
        LIMIT 1
    ");
    $stmt->execute([$strand['id']]);
    $strand_assignments[$strand['id']] = $stmt->fetch(PDO::FETCH_ASSOC);
}
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
        <a href="admin_strands.php"><i class="fas fa-layer-group"></i><span>Manage Strands</span></a>
        <a href="admin_subjects.php"><i class="fas fa-book"></i><span>Subjects</span></a>
        <a href="student_teacher.php"><i class="fas fa-users"></i><span>Students/Teachers</span></a>
        <a href="../admin_login.php" onclick="return confirm('Logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>LOGOUT</span></a>
    </nav>
</div>

<section class="manage-strands">
    <h1>Manage Strands</h1>
    <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

    <form action="" method="post">
        <input type="text" name="strand_name" placeholder="Enter Strand Name" required>
        <button type="submit" name="add_strand">Add Strand</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Strand Name</th>
                <th>Assigned Teacher</th>
                <th>Assign Teacher</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($strands as $strand): 
                $assigned = $strand_assignments[$strand['id']] ?? null;
            ?>
            <tr>
                <td><?= htmlspecialchars($strand['name']); ?></td>
                <td><?= $assigned ? htmlspecialchars($assigned['name']) : 'Unassigned'; ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="strand_id" value="<?= $strand['id']; ?>">
                        <select name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['id']; ?>"
                                    <?= $assigned && $assigned['id'] == $teacher['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($teacher['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="assign_teacher">Assign</button>
                    </form>
                </td>
                <td>
                    <a href="?delete=<?= $strand['id']; ?>" onclick="return confirm('Delete this strand?');" class="delete-btn">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .header { display: flex; justify-content: center; align-items: center; font-size: 24px; font-weight: bold; padding: 15px 0; background-color: #f4f4f4; position: fixed; width: 100%; top: 0; z-index: 1000; }
    .manage-strands { width: 80%; max-width: 1020px; margin: 80px auto 20px; text-align: center; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); float: right; }
    h1 { font-size: 28px; }
    .message { color: green; font-weight: bold; font-size: 18px; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; font-size: 18px; }
    th, td { padding: 14px; border: 1px solid #ddd; }
    th { background-color: #f4f4f4; font-size: 20px; }
    .delete-btn { color: white; text-decoration: none; background: red; padding: 8px 12px; font-size: 16px; border-radius: 5px; }
    .delete-btn:hover { background: darkred; }
</style>

<script src="../js/admin_script.js"></script>
</body>
</html>
