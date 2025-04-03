<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all teachers
$fetch_teachers = $conn->prepare("SELECT id, name FROM tutors");
$fetch_teachers->execute();
$teachers = $fetch_teachers->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Header */
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

        /* Main content */
        .manage-subjects {
            margin-left: 270px;
            margin-top: 80px;
            padding: 20px;
            text-align: left; /* Align text to the left */
        }

        .manage-subjects h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Teacher selection boxes - Aligned to the left */
        .box-container {
            display: flex;
            flex-direction: column; /* Stack boxes vertically */
            align-items: flex-start; /* Align to the left */
            gap: 15px;
            max-width: 400px; /* Set a reasonable width */
        }

        .box {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 18px;
            transition: 0.3s;
            height: 60px;
            width: 100%; /* Make it take full width of container */
            max-width: 400px; /* Ensure consistency */
        }

        .box:hover {
            background: #2980b9;
        }

        .box h3 {
            font-size: 16px;
            margin: 0;
            text-align: center;
            max-width: 90%;
            word-wrap: break-word;
            line-height: 1.4;
        }

        /* Responsive Design */
        @media (max-width: 800px) {
            .side-bar {
                width: 200px;
            }
            .header {
                left: 200px;
                width: calc(100% - 200px);
            }
            .manage-subjects {
                margin-left: 210px;
            }
        }

        @media (max-width: 600px) {
            .side-bar {
                width: 180px;
            }
            .header {
                left: 180px;
                width: calc(100% - 180px);
            }
            .manage-subjects {
                margin-left: 190px;
            }
        }
    </style>
</head>
<body>

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

<header class="header">
    PTCI ONLINE LEARNING MATERIAL SYSTEM
</header>

<section class="manage-subjects">
    <h1>Select a Teacher</h1>
    <div class="box-container">
        <?php foreach ($teachers as $teacher) { ?>
            <a href="admin_classes.php?tutor_id=<?= $teacher['id']; ?>" class="box">
                <h3><?= htmlspecialchars($teacher['name']); ?></h3>
            </a>
        <?php } ?>
    </div>
</section>

</body>
</html>
