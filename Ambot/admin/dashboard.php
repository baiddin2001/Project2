<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
   exit;
}

// Check if 'users' table has 'strand' column
$check_users_columns = $conn->query("SHOW COLUMNS FROM users LIKE 'strand'");
if ($check_users_columns->rowCount() > 0) {
   $select_humms_users = $conn->prepare("SELECT * FROM users WHERE strand = 'HUMMS'");
   $select_humms_users->execute();
   $total_humms_users = $select_humms_users->rowCount();

   $select_ict_users = $conn->prepare("SELECT * FROM users WHERE strand = 'ICT'");
   $select_ict_users->execute();
   $total_ict_users = $select_ict_users->rowCount();
} else {
   $total_humms_users = 0;
   $total_ict_users = 0;
}

// Check if 'classes' table exists before querying
$check_classes_table = $conn->query("SHOW TABLES LIKE 'classes'");
if ($check_classes_table->rowCount() > 0) {
   $select_humms_classes = $conn->prepare("SELECT * FROM classes WHERE tutor_id = ? AND strand = 'HUMMS'");
   $select_humms_classes->execute([$tutor_id]);

   $select_ict_classes = $conn->prepare("SELECT * FROM classes WHERE tutor_id = ? AND strand = 'ICT'");
   $select_ict_classes->execute([$tutor_id]);
} else {
   $select_humms_classes = [];
   $select_ict_classes = [];
}

// Count total content, playlists, and comments
$select_contents = $conn->prepare("SELECT * FROM content WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM playlist WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();

$select_comments = $conn->prepare("SELECT * FROM comments WHERE tutor_id = ?");
$select_comments->execute([$tutor_id]);
$total_comments = $select_comments->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="../css/admin_style.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">
   <h1 class="heading">Dashboard</h1>
   <div class="box-container">

      <div class="box">
         <h3><?= $total_contents; ?></h3>
         <p>Total Number of Resources</p>
         <a href="add_content.php" class="btn">Add New Resources</a>
      </div>

      <div class="box">
         <h3><?= $total_comments; ?></h3>
         <p>Total Comments</p>
         <a href="comments.php" class="btn">View Comments</a>
      </div>

      <div class="box">
         <h3><?= $total_playlists; ?></h3>
         <p>Total Number of Subjects</p>
         <a href="select_strand.php" class="btn">Add Subject</a>
      </div>
</section>

<div id="classPopup" class="popup">
   <div class="popup-content">
      <span class="close" onclick="closePopup()">&times;</span>
      <h2>Select Class</h2>
      <div id="classList"></div>
   </div>
</div>

<script>
function showClassPopup(strand) {
   let classList = document.getElementById("classList");
   classList.innerHTML = "";
   
   let classes = strand === "HUMMS" ? <?= json_encode($select_humms_classes->fetchAll(PDO::FETCH_ASSOC)) ?> : <?= json_encode($select_ict_classes->fetchAll(PDO::FETCH_ASSOC)) ?>;
   
   if (classes.length > 0) {
      classes.forEach(classData => {
         let btn = document.createElement("button");
         btn.textContent = classData.class_name;
         btn.onclick = function() {
            window.location.href = `users.php?strand=${strand}&class_id=${classData.id}`;
         };
         classList.appendChild(btn);
      });
   } else {
      classList.innerHTML = "<p>No classes available.</p>";
   }
   
   document.getElementById("classPopup").style.display = "block";
}

function closePopup() {
   document.getElementById("classPopup").style.display = "none";
}
</script>

<style>
.popup {
   display: none;
   position: fixed;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   background: white;
   padding: 20px;
   border-radius: 8px;
   box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
   z-index: 1000;
}
.popup-content {
   text-align: center;
}
.popup .close {
   position: absolute;
   top: 10px;
   right: 10px;
   cursor: pointer;
   font-size: 20px;
}
.popup button {
   display: block;
   width: 100%;
   padding: 10px;
   margin: 5px 0;
   background: #4CAF50;
   color: white;
   border: none;
   cursor: pointer;
   border-radius: 5px;
}
.popup button:hover {
   background: #45a049;
}
</style>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
