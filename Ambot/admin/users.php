<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
 }else{
    $tutor_id = '';
    header('location:login.php');
 }
 
if (!isset($_GET['class_id']) || !isset($_GET['strand'])) {
    header('location:dashboard.php');
    exit();
}

$class_id = $_GET['class_id'];
$strand = $_GET['strand'];

$select_students = $conn->prepare("SELECT name, email, strand, class_id, image FROM users WHERE class_id = ? AND strand = ?");
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
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<section class="student-list">
    <h1 class="heading">Students in Class</h1>
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Strand</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><img src="../uploaded_files/<?= htmlspecialchars($student['image']) ?>" alt="Student Photo" width="50"></td>
                <td><?= htmlspecialchars($student['name']) ?></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars($student['strand']) ?></td>
                <td><?= htmlspecialchars($student['class_id']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($students)): ?>
            <tr><td colspan="5">No students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
