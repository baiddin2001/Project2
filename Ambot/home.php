<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/box.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<section class="quick-select">

   
<div class="box-container" style="background-color: rgba(221, 225, 221, 0.56); border-radius: 10px;">

            <?php
         if ($user_id != '') {
         ?>
            <div class="box">
               <h3 class="title1">Your Likes and Comments</h3>
               <p>Total Likes: <span><?= $total_likes; ?></span></p>
               <a href="likes.php" class="inline-btn">View Likes</a>
               <p>Total Comments: <span><?= $total_comments; ?></span></p>
               <a href="comments.php" class="inline-btn">View Comments</a>
               <p>Saved Playlist: <span><?= $total_bookmarked; ?></span></p>
               <a href="bookmark.php" class="inline-btn">View Bookmark</a>
            </div>
            <section class="courses">

         <!-- <h1 class="heading">latest courses</h1> -->

   <<div class="notification-container">
         <h3 class="title1">Announcement</h3>
         <?php
         // Get student's strand and class_id
         $get_student = $conn->prepare("SELECT strand, class_id FROM `users` WHERE id = ?");
         $get_student->execute([$user_id]);
         $student_data = $get_student->fetch(PDO::FETCH_ASSOC);

         if ($student_data) {
            $strand = $student_data['strand'];
            $class_id = $student_data['class_id'];

            // Fetch courses that match the student's strand and class_id
            $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? AND strand = ? AND class_id = ? ORDER BY date DESC LIMIT 3");
            $select_courses->execute(['active', $strand, $class_id]);

            if($select_courses->rowCount() > 0){
               while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
                  $course_id = $fetch_course['id'];

                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="notification-box info">
               <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="Tutor Image">
               <div class="content">
                  <h3>New Subject: <?= $fetch_course['title']; ?></h3>
                  <p>By <?= $fetch_tutor['name']; ?> on <?= $fetch_course['date']; ?></p>
                  <a href="playlist.php?get_id=<?= $course_id; ?>">View Course</a>
               </div>
         </div>
         <?php
               }
            } else {
               echo '<p class="empty">No relevant courses found for your strand and class.</p>';
            }
         } else {
            echo '<p class="empty">Unable to retrieve student information.</p>';
         }
         ?>
</div>


<div class="more-btn">
   <a href="courses.php" class="inline-option-btn">View More</a>
</div>
<div style="margin-bottom: 2cm;"></div>

</section>

<?php
} else {
    header("Location: login.php");
    exit(); 
}
?>
   

   </div>

</section>




<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>