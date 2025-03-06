<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
 }else{
    $tutor_id = '';
    header('location:login.php');
    exit();
}

if (!isset($_GET['class_id']) || !isset($_GET['strand'])) {
    header('location:dashboard.php');
    exit();
}

$class_id = $_GET['class_id'];
$strand = $_GET['strand'];

// Fetch class name
$select_class = $conn->prepare("SELECT class_name FROM classes WHERE id = ?");
$select_class->execute([$class_id]);
$class = $select_class->fetch(PDO::FETCH_ASSOC);
$class_name = $class ? $class['class_name'] : 'Unknown Class';

// Handle student deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    $student_id = $_POST['delete_student'];
    $delete_student = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_student->execute([$student_id]);

    // Redirect back to the previous page automatically
    echo "<script>window.history.back();</script>";
    exit();
}

$select_students = $conn->prepare("SELECT id, name, email, strand, image FROM users WHERE class_id = ? AND strand = ?");
$select_students->execute([$class_id, $strand]);
$students = $select_students->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .student-list {
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }
        .student-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            font-size: 1.5rem;
            position: relative;
        }
        .student-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .student-info {
            flex: 1;
            display: flex;
            justify-content: space-around;
        }
        .student-info div {
            text-align: left;
        }
        .student-info h2 {
            font-size: 1.8rem;
            margin: 5px 0;
        }
        .student-info p {
            font-size: 1.5rem;
            color: #333;
            margin: 5px 0;
        }
        .delete-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .delete-button:hover {
            background-color: #c0392b;
        }
        .back-button {
            display: block !important; 
            position: absolute;
            top: 105px; 
            right: 20px;
            background-color: #66bb6a;
            color: black;
            font-weight: 500;
            padding: 8px 25px;
            border: 2px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
            z-index: 1000;
        }
        .back-button:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

<a href="javascript:history.back()" class="back-button">
   <i class="fas fa-arrow-left"></i> Back
</a>

<?php include '../components/admin_header.php'; ?>

<section class="student-list">
    <h1 class="heading">Students in <?= htmlspecialchars($class_name) ?></h1>
    <?php foreach ($students as $student): ?>
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
            <div class="student-card">
                <img src="../uploaded_files/<?= htmlspecialchars($student['image']) ?>" alt="Student Photo">
                <div class="student-info">
                    <h2><?= htmlspecialchars($student['name']) ?></h2>
                    <p>Email: <?= htmlspecialchars($student['email']) ?></p>
                    <p>Strand: <strong><?= htmlspecialchars($student['strand']) ?></strong></p>
                </div>
                <button type="submit" name="delete_student" value="<?= $student['id'] ?>" class="delete-button">Delete</button>
            </div>
        </form>
    <?php endforeach; ?>
    <?php if (empty($students)): ?>
        <p>No students found.</p>
    <?php endif; ?>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
