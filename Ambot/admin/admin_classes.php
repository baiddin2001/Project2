<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id']) || !isset($_GET['tutor_id'])) {
    header('Location: admin_subjects.php');
    exit();
}

$tutor_id = $_GET['tutor_id'];

// Fetch teacher name
$fetch_teacher = $conn->prepare("SELECT name FROM tutors WHERE id = ?");
$fetch_teacher->execute([$tutor_id]);
$teacher = $fetch_teacher->fetch(PDO::FETCH_ASSOC);

// Fetch classes assigned to this teacher, excluding 'sample class' and 'class a'
$fetch_classes = $conn->prepare("SELECT id, class_name FROM classes WHERE tutor_id = ? AND class_name NOT IN ('sample class', 'class a')");
$fetch_classes->execute([$tutor_id]);
$classes = $fetch_classes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .header {
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            background: #f4f4f4;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            z-index: 1000;
        }
        .manage-classes {
            margin-left: 250px;
            padding: 20px;
            margin-top: 80px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .manage-classes h1 {
            margin-bottom: 20px;
            text-align: left;
            font-size: 28px;
            font-weight: bold;
        }
        .box-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 800px;
        }
        .class-box {
            background: rgb(71, 122, 66);
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }
        .class-box:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        .empty-message {
            text-align: center;
            font-size: 18px;
            color: #e74c3c;
            font-weight: bold;
            margin-top: 20px;
        }
        .back-button {
            position: fixed;
            top: 13px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 2000;  /* Increased z-index to ensure visibility */
            transition: 0.3s ease-in-out;
        }
        .back-button {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #28a745;
        }
        @media (max-width: 800px) {
            .box-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 500px) {
            .box-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <span class="logo">PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
</header>

<a href="admin_subjects.php" class="back-button">Back</a>

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

<section class="manage-classes">
    <h1>Classes of <?= htmlspecialchars($teacher['name']); ?></h1>
    <div class="box-container">
        <?php if (!empty($classes)) { ?>
            <?php foreach ($classes as $class) { ?>
                <a href="admin_subject_list.php?class_id=<?= $class['id']; ?>" class="class-box">
                    <h3><?= htmlspecialchars($class['class_name']); ?></h3>
                </a>
            <?php } ?>
        <?php } else { ?>
            <p class="empty-message">No classes created yet.</p>
        <?php } ?>
    </div>
</section>

</body>
</html>
