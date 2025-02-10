<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

if(isset($_POST['save_list'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];
      $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

      $select_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
      $select_list->execute([$user_id, $list_id]);

      if($select_list->rowCount() > 0){
         $remove_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
         $remove_bookmark->execute([$user_id, $list_id]);
         $message[] = 'Playlist removed!';
      }else{
         $insert_bookmark = $conn->prepare("INSERT INTO `bookmark`(user_id, playlist_id) VALUES(?,?)");
         $insert_bookmark->execute([$user_id, $list_id]);
         $message[] = 'Playlist saved!';
      }
   }else{
      $message[] = 'Please login first!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlist</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/Subject_student.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- this is for the video area -->
<section class="courses1">
   <h1 class="heading1" style="color: black;">Playlist Videos</h1>
   
   <div class="box-container1">
         <?php
         $select_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ? AND video IS NOT NULL AND video != '' AND video != 'none' ORDER BY date DESC");
         $select_content->execute([$get_id, 'active']);

         if($select_content->rowCount() > 0){
            while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){  
               
               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_content['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="box-container1">
            <div class="box1">
               <div class="tutor1">
                  <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
                  <div>
                     <h3 style="color: black;"> <?= $fetch_tutor['name']; ?> </h3>
                     <span><?= $fetch_content['date']; ?></span>
                  </div>
               </div>
               <img src="uploaded_files/<?= $fetch_content['thumb']; ?>" class="thumb1" alt="">
               <h3 style="color: black;" class="title1"> <?= $fetch_content['title']; ?> </h3>
               <a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>" class="inline-btn1">Watch Video</a>
            </div>
         </div>
         <?php
      }
   } else {
      echo '<p class="empty1">No videos added yet!</p>';
   }
?>

   </div>
</section>


<!-- Downloadable Files Section -->
<section class="courses1">
   <h1 class="heading1" style="color: black;">Downloadable Files</h1>
   <div class="box-container1">
      <?php
         $select_files = $conn->prepare("SELECT * FROM content WHERE playlist_id = ? AND file IS NOT NULL AND file != '' AND file != 'none'");
         $select_files->execute([$get_id]);

         if ($select_files->rowCount() > 0) {
            while ($fetch_file = $select_files->fetch(PDO::FETCH_ASSOC)) {  
      ?>
      <div class="box1">
         <i class="fas fa-file"></i>
         <h3 style="color: black;" class="title1"><?= $fetch_file['title']; ?></h3>
         <a href="uploaded_files/<?= $fetch_file['file']; ?>" download class="inline-btn1">Download</a>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty1">No files uploaded yet!</p>';
         }
      ?>
   </div>
</section>


<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
