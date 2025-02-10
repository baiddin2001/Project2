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
   <title>About</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images\about.svg" alt="">
      </div>

      <div class="content">
         <h3>Why choose PTCI?</h3>
         <p>Since 1995, Palawan Technological College Incorporated (PTCI) has been a cornerstone of quality education, proudly located on Malvar Street in Puerto Princesa City, Palawan, committed to empowering students and shaping futures through innovative learning</p>
         <a href="courses.php"  class="inline-btn" style="background-color:rgb(40, 98, 42); color: white; border: none; padding: 10px 20px; border-radius: 5px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); cursor: pointer;">our courses</a>
      </div>

   </div>

   <div class="box-container">

      <div class="box" style="background-color: rgba(146, 247, 143, 0.32); border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
         <i style="color:Black;" class="fas fa-graduation-cap"></i>
         <div>
            <h3 style="color:Black;">100+</h3>
            <span style="color:Black;">online courses</span>
         </div>
      </div>

      <div class="box" style="background-color: rgba(146, 247, 143, 0.32); border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
         <i style="color:Black;" class="fas fa-user-graduate" ></i>
         <div>
            <h3 style="color:Black;" >500+</h3>
            <span style="color:Black;">brilliants students</span>
         </div>
      </div>

      <div class="box" style="background-color: rgba(146, 247, 143, 0.32); border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
         <i style="color:Black;" class="fas fa-chalkboard-user"></i>
         <div>
            <h3 style="color:Black;">12</h3>
            <span style="color:Black;">expert teachers</span>
         </div>
      </div>

      </div>

   </div>

</section>

<!-- about section ends -->












<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>