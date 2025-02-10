<?php
include '../components/connect.php';

// Check if tutor is logged in
if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
    exit();
}

// Get strand from URL or redirect
if (isset($_GET['strand'])) {
    $strand = $_GET['strand'];
} else {
    $strand = '';
    header('location:select_strand.php'); // Redirect if no strand is selected
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $id = unique_id();
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);
    $class = $_POST['class'];
    $class = filter_var($class, FILTER_SANITIZE_STRING);

    // Image upload
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename;

    // Insert into database with strand
    $add_playlist = $conn->prepare("INSERT INTO `playlist` (id, tutor_id, title, description, thumb, status, strand, class) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $add_playlist->execute([$id, $tutor_id, $title, $description, $rename, $status, $strand, $class]);

    move_uploaded_file($image_tmp_name, $image_folder);

    $message[] = 'New subject added for ' . htmlspecialchars($strand) . '!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Subject</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlist-form">
   <h1 class="heading">Create Subject for <?= htmlspecialchars($strand); ?></h1>

   <form action="add_playlist.php?strand=<?= urlencode($strand); ?>" method="post" enctype="multipart/form-data">
      <p>Subject Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Select Status --</option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Select Class <span>*</span></p>
      <select name="class" class="box" required>
         <option value="" selected disabled>-- Select Class --</option>
         <option value="class1">Class 1</option>
         <option value="class2">Class 2</option>
         <option value="class3">Class 3</option>
      </select>
      <p>Subject title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter Subject Title" class="box">
      <p>Subject description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write Description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Subject Cover <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
      <input type="submit" value="Create Subject" name="submit" class="btn">
   </form>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
</body>
</html>
