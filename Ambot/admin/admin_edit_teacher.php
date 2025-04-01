<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}

if (isset($_GET['id'])) {
    $tutor_id = $_GET['id'];
    
    // Fetch tutor details for editing
    $select_tutor = $conn->prepare("SELECT * FROM tutors WHERE id = ? LIMIT 1");
    $select_tutor->execute([$tutor_id]);
    $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
    
    if (!$fetch_tutor) {
        header('Location: student_teacher.php'); // Redirect if tutor not found
        exit();
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    $status = $_POST['status'];

    // Update tutor details
    if (!empty($name)) {
        $update_name = $conn->prepare("UPDATE tutors SET name = ? WHERE id = ?");
        $update_name->execute([$name, $tutor_id]);
    }

    if (!empty($email)) {
        $select_email = $conn->prepare("SELECT email FROM tutors WHERE email = ? AND id != ?");
        $select_email->execute([$email, $tutor_id]);
        if ($select_email->rowCount() > 0) {
            $message[] = 'Email is already taken.';
        } else {
            $update_email = $conn->prepare("UPDATE tutors SET email = ? WHERE id = ?");
            $update_email->execute([$email, $tutor_id]);
        }
    }

    // Update tutor status
    if (isset($status)) {
        $update_status = $conn->prepare("UPDATE tutors SET status = ? WHERE id = ?");
        $update_status->execute([$status, $tutor_id]);
    }

    // Handle password update
    if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['cpass'])) {
        $old_pass = sha1($_POST['old_pass']);
        $new_pass = sha1($_POST['new_pass']);
        $cpass = sha1($_POST['cpass']);
        
        if ($old_pass !== $fetch_tutor['password']) {
            $message[] = 'Old password does not match!';
        } elseif ($new_pass !== $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            $update_pass = $conn->prepare("UPDATE tutors SET password = ? WHERE id = ?");
            $update_pass->execute([$new_pass, $tutor_id]);
            $message[] = 'Password updated successfully!';
        }
    }

    // Handle image update
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = uniqid() . '.' . $ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $rename;

        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            $update_image = $conn->prepare("UPDATE tutors SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $tutor_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            // Remove old image if it exists
            if (!empty($fetch_tutor['image']) && $fetch_tutor['image'] !== $rename) {
                unlink('../uploaded_files/' . $fetch_tutor['image']);
            }
            $message[] = 'Image updated successfully!';
        }
    }

    // Add success message to session
    $_SESSION['success'] = 'Tutor profile updated successfully!';

    // Redirect back to the tutor management page
    header('Location: teacher_list.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tutor Profile</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<style>
    .update-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 12px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.3s ease-in-out;
    }

    .update-btn:hover {
        background-color: #0056b3;
    }

    .header {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        font-weight: bold;
        padding: 15px 0;
        background-color: #f4f4f4;
        width: 100%;
        position: fixed;
        top: 0;
        left: 4cm;
        z-index: 1000;
    }

    .back-button {
        display: block !important;
        position: fixed;
        top: 10px;
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
        z-index: 2000;
    }

    .back-button:hover {
        background-color: #e0e0e0;
    }

    .success-message {
        background-color: #28a745;
        color: white;
        padding: 10px;
        margin: 20px;
        border-radius: 5px;
        text-align: center;
    }

</style>

<body>

<header class="header">
    <span class="logo">PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
</header>
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>

<div class="side-bar">
    <nav class="navbar">
        <a href="admin_strands.php">
            <i class="fas fa-layer-group"></i><span>Manage Strands</span>
        </a>
        <a href="admin_subjects.php">
            <i class="fas fa-book"></i><span>Subjects</span>
        </a>
        <a href="student_teacher.php">
            <i class="fas fa-users"></i><span>Students/Tutors</span>
        </a>
        <a href="../admin_login.php" onclick="return confirm('Logout from this website?');">
            <i class="fas fa-right-from-bracket"></i><span>LOGOUT</span>
        </a>
    </nav>
</div>
<br><br><br><br>
<section class="form-container" style="min-height: calc(100vh - 19rem);">
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="success-message">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']); // Clear the success message after displaying
    }
    ?>

    <form class="register" action="" method="post" enctype="multipart/form-data">
        <h3>Edit Teacher Profile</h3>

        <div class="flex">
            <div class="col">
                <p>Full Name</p>
                <input type="text" name="name" value="<?= htmlspecialchars($fetch_tutor['name']); ?>" class="box">

                <p>Email</p>
                <input type="email" name="email" value="<?= htmlspecialchars($fetch_tutor['email']); ?>" class="box" readonly>

                <p>Status</p>
                <select name="status" class="box">
                    <option value="Active" <?= $fetch_tutor['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?= $fetch_tutor['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col">
                <p>Old Password</p>
                <input type="password" name="old_pass" placeholder="Enter your old password" class="box">

                <p>New Password</p>
                <input type="password" name="new_pass" placeholder="Enter your new password" class="box">

                <p>Confirm Password</p>
                <input type="password" name="cpass" placeholder="Confirm your new password" class="box">
            </div>
        </div>

        <p>Update Profile Picture</p>
        <input type="file" name="image" accept="image/*" class="box">

        <input type="submit" name="submit" value="Update Profile" class="btn">
    </form>
</section>

<?php include '../components/footer.php'; ?>

</body>
</html>
