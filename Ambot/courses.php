<?php

include 'components/connect.php';

// Check if user_id exists in the cookie
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];

   // Fetch the user's strand and class from the database
   $select_user = $conn->prepare("SELECT strand, class_id FROM users WHERE id = ?");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   if($fetch_user){
      $user_strand = $fetch_user['strand'];
      $user_class_id = $fetch_user['class_id'];
   } else {
      $user_strand = '';
      $user_class_id = '';
   }
} else {
   $user_id = '';
   $user_strand = '';
   $user_class_id = '';
}

// Fetch the class name based on class_id
$class_name = 'Unknown Class';
if (!empty($user_class_id)) {
   $select_class = $conn->prepare("SELECT class_name FROM classes WHERE id = ?");
   $select_class->execute([$user_class_id]);
   $fetch_class = $select_class->fetch(PDO::FETCH_ASSOC);
   if ($fetch_class) {
      $class_name = $fetch_class['class_name'];
   }
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

   <div class="box-container1">

      <?php
      $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? AND strand = ? AND class_id = ? ORDER BY date DESC");
      $select_courses->execute(['active', $user_strand, $user_class_id]);

      if($select_courses->rowCount() > 0){
         while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
            $course_id = $fetch_course['id'];
            $tutor_id = $fetch_course['tutor_id'];

            // Fetch tutor details
            $select_tutor = $conn->prepare("SELECT name, image FROM `tutors` WHERE id = ?");
            $select_tutor->execute([$tutor_id]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

            // Default values if tutor data is missing
            $tutor_name = $fetch_tutor ? $fetch_tutor['name'] : 'Unknown Tutor';
            $tutor_image = $fetch_tutor ? $fetch_tutor['image'] : 'default.jpg';
      ?>
      <div class="box1">
         <div class="tutor1">
            <img src="uploaded_files/<?= htmlspecialchars($tutor_image); ?>" alt="Tutor Image">
            <div>
               <h3 style="color: black;"><?= htmlspecialchars($tutor_name); ?></h3>
               <span><?= htmlspecialchars($fetch_course['date']); ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= htmlspecialchars($fetch_course['thumb']); ?>" class="thumb1" alt="Course Thumbnail">
         <h3 style="color: black;" class="title1"><?= htmlspecialchars($fetch_course['title']); ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn1">View Resources</a>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty1">No subjects available for your class!</p>';
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
