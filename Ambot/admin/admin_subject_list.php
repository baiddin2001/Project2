<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Check if a strand is selected and fetch subjects
if (isset($_GET['strand_id'])) {
    $strand_id = $_GET['strand_id'];

    // Fetch the strand name
    $fetch_strand = $conn->prepare("SELECT name FROM strands WHERE id = ?");
    $fetch_strand->execute([$strand_id]);
    $strand = $fetch_strand->fetch(PDO::FETCH_ASSOC);

    // Fetch subjects assigned to this strand
    $fetch_subjects = $conn->prepare("SELECT id, title, description FROM playlist WHERE strand = ?");
    $fetch_subjects->execute([$strand['name']]);
    $subjects = $fetch_subjects->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: admin_strands.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Body */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f7fc,rgb(224, 232, 225));
            color: #2c3e50;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            background:rgb(244, 244, 244);
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            z-index: 1000;
        }

        /* Main content */
        .manage-subjects {
            margin-left: 270px;
            margin-top: 100px;
            padding: 40px;
        }

        .section-box-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .section-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1000px;
            margin-bottom: 40px;
            text-align: center;
        }

        .section-box h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color:linear-gradient(135deg, #f4f7fc,rgb(28, 54, 31));
        }

        .subject-list {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }

        .subject-box {
            background-color:rgb(236, 241, 237);
            border-radius: 15px;
            padding: 20px;
            width: 280px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .subject-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .subject-box h3 {
            font-size: 20px;
            color:linear-gradient(135deg, #f4f7fc,rgb(28, 61, 41));
            margin-bottom: 10px;
        }

        .subject-box p {
            color:rgb(0, 0, 0);
            font-size: 16px;
        }

        .empty {
            color: #e74c3c;
            font-style: italic;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .side-bar {
                width: 200px;
            }

            .header {
                left: 200px;
                width: calc(100% - 200px);
            }

            .manage-subjects {
                margin-left: 220px;
            }

            .subject-box {
                width: 100%;
                max-width: 300px;
            }
        }

        @media (max-width: 480px) {
            .side-bar {
                width: 180px;
            }

            .manage-subjects {
                margin-left: 200px;
            }

            .subject-box {
                width: 100%;
                max-width: 250px;
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
    <div class="section-box-container">
        <div class="section-box">
            <h1>Subjects for <?= htmlspecialchars($strand['name']); ?></h1>
            <div class="subject-list">
                <?php foreach ($subjects as $subject) { ?>
                    <div class="subject-box">
                        <h3><?= htmlspecialchars($subject['title']); ?></h3>
                        <p><?= htmlspecialchars($subject['description']); ?></p>
                    </div>
                <?php } ?>
                <?php if (empty($subjects)) { ?>
                    <p class="empty">No subjects added for this strand.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

</body>
</html>
