<?php

include 'components/connect.php';

// Check if user_id exists in the cookie
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];

   // Fetch the user's strand from the database
   $select_user = $conn->prepare("SELECT strand FROM users WHERE id = ?");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   // If user exists, get their strand
   if($fetch_user){
      $user_strand = $fetch_user['strand'];
   } else {
      $user_strand = ''; // Default to empty if not found
   }
} else {
   $user_id = '';
   $user_strand = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Courses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/Subject_student.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->

<section class="courses1">

   <h1 class="heading1" style="color: black;">Available Subjects for <?= htmlspecialchars($user_strand); ?></h1>

   <div class="box-container1">

      <?php
      // Fetch courses that match the student's strand
      $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? AND strand = ? ORDER BY date DESC");
      $select_courses->execute(['active', $user_strand]);

      if($select_courses->rowCount() > 0){
         while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
            $course_id = $fetch_course['id'];

            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_tutor->execute([$fetch_course['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box1">
         <div class="tutor1">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3 style="color: black;"><?= htmlspecialchars($fetch_tutor['name']); ?></h3>
               <span><?= htmlspecialchars($fetch_course['date']); ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= htmlspecialchars($fetch_course['thumb']); ?>" class="thumb1" alt="">
         <h3 style="color: black;" class="title1"><?= htmlspecialchars($fetch_course['title']); ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn1">View Resources</a>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty1">No courses available for your strand!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
