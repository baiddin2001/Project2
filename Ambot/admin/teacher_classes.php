<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id']) || !isset($_GET['tutor_id'])) {
    header('Location: admin_subjects.php');
    exit();
}

$tutor_id = $_GET['tutor_id'];

// Fetch teacher's name
$fetch_teacher = $conn->prepare("SELECT name FROM tutors WHERE id = ?");
$fetch_teacher->execute([$tutor_id]);
$teacher = $fetch_teacher->fetch(PDO::FETCH_ASSOC);
$teacher_name = $teacher['name'] ?? 'Unknown Teacher';

// Fetch teacher's assigned classes
$fetch_classes = $conn->prepare("SELECT id, class_name FROM classes WHERE tutor_id = ?");
$fetch_classes->execute([$tutor_id]);
$classes = $fetch_classes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($teacher_name); ?> - Assigned Classes</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<header class="header">
    <span class="logo"><i class="fas fa-book"></i> PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
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
    <h1><?= htmlspecialchars($teacher_name); ?>'s Assigned Classes</h1>
    
    <div class="box-container">
        <?php if (!empty($classes)) { ?>
            <?php foreach ($classes as $class) { ?>
                <a href="playlists.php?class_id=<?= $class['id']; ?>" class="box">
                    <h3><?= htmlspecialchars($class['class_name']); ?></h3>
                </a>
            <?php } ?>
        <?php } else { ?>
            <p class="empty">No classes assigned yet.</p>
        <?php } ?>
    </div>
</section>

<!-- Styles -->
<style>
    .manage-classes {
        text-align: center;
        padding: 20px;
    }

    .box-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        justify-content: center;
        padding: 20px;
    }

    .box {
        background: #f8f8f8;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        font-size: 20px;
        cursor: pointer;
        text-decoration: none;
        color: black;
        font-weight: bold;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
    }

    .box:hover {
        background: #e0e0e0;
    }
</style>

</body>
</html>
