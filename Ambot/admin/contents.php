<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);
   if($verify_video->rowCount() > 0){
      $fetch_video = $verify_video->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_video['thumb']);
      unlink('../uploaded_files/'.$fetch_video['video']);
      $conn->prepare("DELETE FROM `likes` WHERE content_id = ?")->execute([$delete_id]);
      $conn->prepare("DELETE FROM `comments` WHERE content_id = ?")->execute([$delete_id]);
      $conn->prepare("DELETE FROM `content` WHERE id = ?")->execute([$delete_id]);
      $message[] = 'video deleted!';
   }else{
      $message[] = 'video already deleted!';
   }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      .strand-humms {
    background: #e0f7fa; 
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 5px solid #0288d1;
}

.strand-ict {
    background:rgb(207, 240, 209); 
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 5px solid #388e3c;
}

.strand-title {
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}
   </style>
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<section class="contents">
   <h1 class="heading">List of Resources</h1>
   <div class="box-container">
      <div class="box" style="text-align: center;">
         <h3 class="title">Create New Resources</h3>
         <a href="add_content.php" class="btn">Add Resources</a>
      </div>
      
      <?php
      $select_videos = $conn->prepare("SELECT c.*, p.title AS subject, p.strand 
                                       FROM `content` c 
                                       INNER JOIN `playlist` p ON c.playlist_id = p.id 
                                       WHERE c.tutor_id = ? 
                                       ORDER BY p.strand, p.title, c.date DESC");
      $select_videos->execute([$tutor_id]);
      $resources = $select_videos->fetchAll(PDO::FETCH_ASSOC);
      
      $current_strand = '';

      foreach ($resources as $video) {
         if ($current_strand != $video['strand']) {
            if ($current_strand != '') {
               echo '</div>'; 
            }
            $current_strand = $video['strand'];

            $strand_class = ($current_strand == 'HUMMS') ? 'strand-humms' : 'strand-ict';

            echo "<div class='strand-group $strand_class'>";
            echo "<h2 class='strand-title'>$current_strand Resources</h2>";
         }
         ?>
         <div class="box">
            <div class="flex">
               <div><i class="fas fa-dot-circle" style="color: <?= $video['status'] == 'active' ? 'limegreen' : 'red'; ?>;"></i><span><?= $video['status']; ?></span></div>
               <div><i class="fas fa-calendar"></i><span><?= $video['date']; ?></span></div>
            </div>
            <img src="../uploaded_files/<?= $video['thumb']; ?>" class="thumb" alt="">
            <h3 class="title">[<?= $video['subject']; ?>] <?= $video['title']; ?></h3>
            <form action="" method="post" class="flex-btn">
               <input type="hidden" name="video_id" value="<?= $video['id']; ?>">
               <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
            </form>
            <a href="view_content.php?get_id=<?= $video['id']; ?>" class="btn">View Resources</a>
         </div>
         <?php
      }
      if ($current_strand != '') {
         echo '</div>'; 
      }
      ?>
   </div>
</section>
<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
