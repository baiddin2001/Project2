<?php
include '../components/connect.php';

if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    $fetch_subjects = $conn->prepare("SELECT title FROM playlist WHERE class_id = ?");
    $fetch_subjects->execute([$class_id]);
    $subjects = $fetch_subjects->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($subjects);
}
?>
