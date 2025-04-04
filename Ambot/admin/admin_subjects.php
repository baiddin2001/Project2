<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all strands
$fetch_strands = $conn->prepare("SELECT id, name FROM strands");
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
    <style>
        /* General styles for the page */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f4f4f4, #e0e0e0); /* Subtle background gradient */
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .navbar a {
            display: block;
            padding: 15px;
            text-decoration: none;
            color: #ecf0f1;
            font-size: 18px;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #2980b9;
        }

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

        /* Main Content */
        .manage-strands {
            margin-left: 270px;
            margin-top: 100px;
            padding: 30px;
        }

        .section-box-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            flex-direction: column;
        }

        .section-box {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
            margin-bottom: 40px;
            text-align: center;
        }

        .section-box h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Strand Buttons */
        .box-container {
            display: flex;
            justify-content: center; 
            align-items: center; 
            gap: 20px;
            flex-wrap: wrap;
            width: 100%;
            margin-top: 20px; 
        }

        .box {
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            padding: 20px 40px;
            border-radius: 10px;
            font-size: 18px;
            width: 250px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .box:hover {
            background-color: #2ecc71;
            transform: translateY(-5px);
        }

        .box h3 {
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .side-bar {
                width: 200px;
            }

            header {
                left: 200px;
                width: calc(100% - 200px);
            }

            .manage-strands {
                margin-left: 220px;
            }

            .box {
                width: 100%;
                max-width: 300px;
            }
        }

        @media (max-width: 480px) {
            .side-bar {
                width: 180px;
            }

            .manage-strands {
                margin-left: 200px;
            }

            .box {
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

<section class="manage-strands">
    <div class="section-box-container">
        <div class="section-box">
            <h1>Select a Strand</h1>
            <div class="box-container">
                <?php foreach ($strands as $strand) { ?>
                    <a href="admin_subject_list.php?strand_id=<?= $strand['id']; ?>" class="box">
                        <h3><?= htmlspecialchars($strand['name']); ?></h3>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

</body>
</html>
