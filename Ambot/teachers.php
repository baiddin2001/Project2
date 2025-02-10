<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Instructors</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers section starts  -->

<section class="teachers">

   <h1 class="heading">Expert Instructors</h1>

   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="search instructors..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <div class="box-container" style="background-color: rgba(146, 247, 143, 0.32); border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">

      <!-- <div class="box offer">
         <h3>Become a Instructor</h3>
         <p>Join PTCI College as a instructor and inspire the next generation of learners by sharing your expertise, shaping futures, and making a lasting impact in education.</p>
         <a href="admin/register.php" class="inline-btn">get started</a>
      </div> -->

      <?php
         $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
         $select_tutors->execute();
         if($select_tutors->rowCount() > 0){
            while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){

               $tutor_id = $fetch_tutor['id'];

               $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
               $count_playlists->execute([$tutor_id]);
               $total_playlists = $count_playlists->rowCount();

               $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
               $count_contents->execute([$tutor_id]);
               $total_contents = $count_contents->rowCount();

               $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
               $count_likes->execute([$tutor_id]);
               $total_likes = $count_likes->rowCount();

               $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
               $count_comments->execute([$tutor_id]);
               $total_comments = $count_comments->rowCount();
      ?>
    <div class="box" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 12px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out;">
   <div class="tutor" style="display: flex; align-items: center; gap: 15px;">
      <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
      <div>
         <h3 style="font-size: 18px; color: #333; font-weight: 600; margin: 0;"><?= $fetch_tutor['name']; ?></h3>
         <span style="font-size: 14px; color: #777;"><?= $fetch_tutor['profession']; ?></span>
      </div>
   </div>
   <div style="margin-top: 15px; color: #555;">
      <p style="font-size: 14px; color: #2d2d2d;">Playlists: <span style="font-weight: bold; color: #2d2d2d;"><?= $total_playlists; ?></span></p>
      <p style="font-size: 14px; color: #2d2d2d;">Total Videos: <span style="font-weight: bold; color: #2d2d2d;"><?= $total_contents ?></span></p>
      <p style="font-size: 14px; color: #2d2d2d;">Total Likes: <span style="font-weight: bold; color: #2d2d2d;"><?= $total_likes ?></span></p>
      <p style="font-size: 14px; color: #2d2d2d;">Total Comments: <span style="font-weight: bold; color: #2d2d2d;"><?= $total_comments ?></span></p>
   </div>
   <form action="tutor_profile.php" method="post" style="margin-top: 15px;">
      <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
      <input type="submit" value="View Profile" name="tutor_fetch" class="inline-btn" style="background-color: #007bff; color: #fff; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background-color 0.3s;">
   </form>
</div>

      <?php
            }
         }else{
            echo '<p class="empty">no tutors found!</p>';
         }
      ?>
       <div style="margin-bottom: 2cm;"></div>

   </div>

</section>

<!-- teachers section ends -->






























<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>