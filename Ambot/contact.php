<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $msg]);

   if($select_contact->rowCount() > 0){
      $message[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $msg]);
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

<div class="row" >

      <div class="image" >
         <img src="images/contact.svg" alt="">
      </div>

      <form action="" method="post" style="background: linear-gradient(to right, rgba(34, 139, 34, 0.57), rgba(60, 179, 114, 0.52)); box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3); border-radius: 10px;">
         <h3>Get in Touch!</h3>
         <input type="text" placeholder="Enter your name" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="Enter your email" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="99999999999" placeholder="Enter your number" required maxlength="11" name="number" class="box">
         <textarea name="msg" class="box" placeholder="Enter your message" required cols="30" rows="10" maxlength="1000"></textarea>
         
         
         <input type="submit" value="Send Message" class="inline-btn" name="submit" 
       style="background-color:rgb(40, 98, 42); color: white; border: none; padding: 10px 20px; border-radius: 5px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); cursor: pointer;">
      </form>

   </div>

      <div class="box-container" style="display: flex; justify-content: center; align-items: center; gap: 30px; flex-wrap: wrap; text-align: center;">

      <div class="box" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 12px; background-color: #fff; width: 300px; height:200px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
         <i class="fas fa-phone" style="font-size: 30px; color:rgb(0, 255, 81);"></i>
         <h3 style="font-size: 18px; color: #333; font-weight: 600;">Phone Number</h3>
         <a href="tel:0955 986 9826" style="font-size: 16px; color: #007bff; text-decoration: none;">0955 986 9826</a>
      </div>

      <div class="box" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 12px; background-color: #fff; width: 300px; height:200px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
         <i class="fas fa-envelope" style="font-size: 30px; color:rgb(0, 255, 81);"></i>
         <h3 style="font-size: 18px; color: #333; font-weight: 600;">Email Address</h3>
         <a href="mailto:ptci_ppc@yahoo.com" style="font-size: 16px; color: #007bff; text-decoration: none;">ptci_ppc@yahoo.com</a>
      </div>

      <div class="box" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 12px; background-color: #fff; width: 300px; height:200px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
         <i class="fas fa-map-marker-alt" style="font-size: 30px; color:rgb(0, 255, 81)"></i>
         <h3 style="font-size: 18px; color: #333; font-weight: 600;">Office Address</h3>
         <a href="https://www.google.com/maps/place/Palawan+Technological+College+Inc./@9.7457862,118.7386164,17z/data=!4m6!3m5!1s0x33b563c2c589ea8b:0xeedcd9f30debb84b!8m2!3d9.7457809!4d118.7411913!16s%2Fg%2F1tykrxmj?entry=ttu&g_ep=EgoyMDI0MTEyNC4xIKXMDSoASAFQAw%3D%3D" style="font-size: 16px; color: #007bff; text-decoration: none;">245, Malvar St., Puerto Princesa, Philippines, 5300</a>
      </div>

      </div>


</section>

<!-- contact section ends -->











<?php include 'components/footer.php'; ?>  

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>