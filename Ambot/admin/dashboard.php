<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->execute([$tutor_id]);
$total_comments = $select_comments->rowCount();

$select_humms_users = $conn->prepare("SELECT * FROM `users` WHERE strand = 'HUMMS'");
$select_humms_users->execute();
$total_humms_users = $select_humms_users->rowCount();

$select_ict_users = $conn->prepare("SELECT * FROM `users` WHERE strand = 'ICT'");
$select_ict_users->execute();
$total_ict_users = $select_ict_users->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="dashboard">
   <h1 class="heading">dashboard</h1>
   <div class="box-container">
      <div class="box">
         <h3><?= $total_contents; ?></h3>
         <p>Total Number of Resources</p>
         <a href="add_content.php" class="btn">Add New Resources</a>
      </div>

      <div class="box">
         <h3><?= $total_playlists; ?></h3>
         <p>Total Number of Subjects</p>
         <a href="select_strand.php" class="btn">Add Subject</a>
      </div>

      <div class="box">
         <h3><?= $total_comments; ?></h3>
         <p>Total Comments</p>
         <a href="comments.php" class="btn">View Comments</a>
      </div>
      
      <div class="box">
         <h3><?= $total_humms_users; ?></h3>
         <p>Total Number of Users in HUMMS</p>
         <a href="users.php?strand=HUMMS" class="btn">View Users</a>
      </div>
      
      <div class="box">
         <h3><?= $total_ict_users; ?></h3>
         <p>Total Number of Users in ICT</p>
         <a href="users.php?strand=ICT" class="btn">View Users</a>
      </div>
   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>