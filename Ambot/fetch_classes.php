<?php
include 'components/connect.php';

if (isset($_GET['strand'])) {
    $strand = $_GET['strand'];
    $query = $conn->prepare("SELECT id, class_name FROM classes WHERE strand = ?");
    $query->execute([$strand]);

    $classes = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($classes);
}
?>
