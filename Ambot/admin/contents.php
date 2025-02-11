<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if (isset($_POST['delete_video'])) {
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   // Fetch all details in one query
   $verify_video = $conn->prepare("SELECT thumb, video FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);

   if ($verify_video->rowCount() > 0) {
       $fetch_video = $verify_video->fetch(PDO::FETCH_ASSOC);

       // Delete thumbnail if exists
       if (!empty($fetch_video['thumb']) && file_exists('../uploaded_files/' . $fetch_video['thumb'])) {
           unlink('../uploaded_files/' . $fetch_video['thumb']);
       }

       // Delete video if exists
       if (!empty($fetch_video['video']) && file_exists('../uploaded_files/' . $fetch_video['video'])) {
           unlink('../uploaded_files/' . $fetch_video['video']);
       }

       // Delete associated data
       $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
       $delete_likes->execute([$delete_id]);

       $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
       $delete_comments->execute([$delete_id]);

       // Delete video record
       $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
       $delete_content->execute([$delete_id]);

       $message[] = 'Video deleted!';
   } else {
       $message[] = 'Video already deleted!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="contents">

   <h1 class="heading">List of Resources</h1>

   <div class="box-container">

   <div class="box" style="text-align: center;">
      <h3 class="title" style="margin-bottom: .5rem;">Create New Resources</h3>
      <a href="add_content.php" class="btn">Add Resources</a>
   </div>

   <?php
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? ORDER BY date DESC");
      $select_videos->execute([$tutor_id]);
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <!-- <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">update</a> -->
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">View Resources</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no resources added yet!</p>';
      }
   ?>

   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>