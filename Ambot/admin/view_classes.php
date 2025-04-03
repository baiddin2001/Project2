<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$teacher_id = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : null;
if (!$teacher_id) {
    header('Location: admin_subjects.php');
    exit();
}

// Fetch classes assigned to the teacher
$fetch_classes = $conn->prepare("
    SELECT c.id AS class_id, c.name AS class_name
    FROM classes c
    JOIN teacher_classes tc ON c.id = tc.class_id
    WHERE tc.teacher_id = ?
");
$fetch_classes->execute([$teacher_id]);
$classes = $fetch_classes->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Classes</title>
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

<section class="manage-classes">
    <h1>Classes for Teacher</h1>
    <div class="box-container">
        <?php foreach ($classes as $class) { ?>
            <div class="box">
                <h3><?= htmlspecialchars($class['class_name']); ?></h3>
                <a href="view_subjects.php?class_id=<?= $class['class_id']; ?>" class="btn">View Subjects</a>
            </div>
        <?php } ?>
    </div>
</section>

</body>
</html>

<style>
    .box-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .box {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        width: 200px;
        text-align: center;
        background-color: #f9f9f9;
    }
    .btn {
        margin-top: 10px;
        display: inline-block;
        padding: 8px 15px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .btn:hover {
        background-color: #0056b3;
    }
</style>
