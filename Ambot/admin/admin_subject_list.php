<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id']) || !isset($_GET['class_id'])) {
    header('Location: admin_subjects.php');
    exit();
}

$class_id = $_GET['class_id'];

// Fetch class name
$fetch_class = $conn->prepare("SELECT class_name FROM classes WHERE id = ?");
$fetch_class->execute([$class_id]);
$class = $fetch_class->fetch(PDO::FETCH_ASSOC);

// Fetch subjects assigned to this class
$fetch_subjects = $conn->prepare("SELECT id, title, description FROM playlist WHERE class_id = ?");
$fetch_subjects->execute([$class_id]);
$subjects = $fetch_subjects->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<header class="header">
    <span class="logo">PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
    <a href="javascript:history.back()" class="back-button">Back</a> <!-- Fixed back button -->
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

<section class="manage-subjects">
    <h1>Subjects for <?= htmlspecialchars($class['class_name']); ?></h1>
    <div class="subject-list">
        <?php foreach ($subjects as $subject) { ?>
            <div class="subject-box">
                <h3><?= htmlspecialchars($subject['title']); ?></h3>
                <p><?= htmlspecialchars($subject['description']); ?></p>
            </div>
        <?php } ?>
        <?php if (empty($subjects)) { ?>
            <p class="empty">No subjects added for this class.</p>
        <?php } ?>
    </div>
</section>

<style>
.header {
    position: fixed;
    top: 0;
    left: 250px;
    width: calc(100% - 250px);
    background: #f4f4f4;
    padding: 15px;
    font-size: 24px;
    font-weight: bold;
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-right: 20px;
}

.header .logo {
    flex-grow: 1;
    text-align: center;
}

.back-button {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    font-weight: bold;
    margin-left: auto;
    cursor: pointer;
}

.back-button:hover {
    background: #218838;
}

.manage-subjects {
    margin-left: 270px;
    padding: 30px;
    margin-top: 80px;
}

.manage-subjects h1 {
    text-align: left;
    font-size: 30px;
    margin-bottom: 30px;
    font-weight: bold;
    color: #333;
    margin-left: 10px;
}

.subject-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 20px;
}

.subject-box {
    background: linear-gradient(135deg, #4CAF50,rgb(54, 124, 56));
    color: white;
    padding: 20px;
    text-align: left;
    font-size: 18px;
    font-weight: bold;
    border-radius: 8px;
    transition: 0.3s ease-in-out;
}

.subject-box:hover {
    background: #2980b9;
    transform: scale(1.05);
}

.empty {
    text-align: center;
    font-size: 20px;
    color: #e74c3c;
    font-weight: bold;
}

@media (max-width: 800px) {
    .side-bar { width: 200px; }
    .header { left: 200px; width: calc(100% - 200px); }
    .manage-subjects { margin-left: 210px; }
    .subject-list { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 600px) {
    .side-bar { width: 180px; }
    .header { left: 180px; width: calc(100% - 180px); }
    .manage-subjects { margin-left: 190px; }
    .subject-list { grid-template-columns: 1fr; }
}
</style>

</body>
</html>
