<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get the class ID from the URL
if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Fetch subjects for the selected class
    $fetch_subjects = $conn->prepare("
    SELECT subjects.name 
    FROM subjects
    JOIN class_subjects ON subjects.id = class_subjects.subject_id
    WHERE class_subjects.class_id = ?
    ");
    $fetch_subjects->execute([$class_id]);
    $subjects = $fetch_subjects->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: admin_subjects.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Subjects</title>
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

<section class="view-subjects">
    <h1>Subjects for Class ID: <?= htmlspecialchars($class_id); ?></h1>

    <div class="subject-list">
        <?php if (empty($subjects)) { ?>
            <p>No subjects assigned to this class.</p>
        <?php } else { ?>
            <ul>
                <?php foreach ($subjects as $subject) { ?>
                    <li><?= htmlspecialchars($subject['name']); ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

    <a href="teacher_classes.php" class="back-btn">Back to Classes</a>
</section>

<!-- Styles -->
<style>
    .view-subjects {
        text-align: center;
        padding: 20px;
    }

    .subject-list ul {
        list-style: none;
        padding: 0;
    }

    .subject-list li {
        font-size: 18px;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .back-btn {
        display: inline-block;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }

    .back-btn:hover {
        background: #0056b3;
    }
</style>

</body>
</html>
