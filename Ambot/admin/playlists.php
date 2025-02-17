<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Get the strand and class from the URL
if(isset($_GET['strand']) && isset($_GET['class_id'])){
   $strand = $_GET['strand'];
   $class_id = $_GET['class_id'];
} else {
   header('location:classes.php?strand=' . $strand); // Redirect if no strand or class is selected
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $strand; ?> Subjects for Class <?= $class_id; ?></title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists">
   <h1 class="heading"><?= $strand; ?> Subjects for Class <?= $class_id; ?></h1>

   <div class="box-container">
      <div class="box" style="text-align: center;">
         <h3 class="title">Create New Subject</h3>
         <a href="add_playlist.php?strand=<?= $strand; ?>&class_id=<?= $class_id; ?>" class="btn">Add Subject</a>
      </div>

      <?php
         // Fetch playlists that are linked to the specific class and strand
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND strand = ? AND class_id = ? ORDER BY date DESC");
         $select_playlist->execute([$tutor_id, $strand, $class_id]);

         if($select_playlist->rowCount() > 0){
            while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
               $playlist_id = $fetch_playlist['id'];
               $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
               $count_videos->execute([$playlist_id]);
               $total_videos = $count_videos->rowCount();
      ?>
      <div class="box">
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= $fetch_playlist['description']; ?></p>
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">View Lessons</a>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">Update</a>
            <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this playlist?');" name="delete">
         </form>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No subjects added yet!</p>';
         }
      ?>
   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
