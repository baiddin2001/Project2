<?php
include '../components/connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    try {
        $delete_student = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_student->execute([$student_id]);

        if ($delete_student->rowCount() > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Student not found or already deleted."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}
?>
