
<?php
include_once '../components/connect.php';
?>
<!-- this is for the log in/Teacher -->

<?php
if(isset($_POST['submit_admin_login'])){

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
 
    $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
    $select_tutor->execute([$email, $pass]);
    $row = $select_tutor->fetch(PDO::FETCH_ASSOC);
    
    if($select_tutor->rowCount() > 0){
      setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
      header('location:dashboard.php');
    }else{
       $message[] = 'incorrect email or password!';
    }
 
 }
?>


<!-- this is for the sign up?Teacher -->

<?php

if(isset($_POST['submit_admin_signup'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $profession = $_POST['profession'];
   $profession = filter_var($profession, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'new tutor registered! please login now';
      }
   }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Teacher Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/login_register_admin.css">


</head>

<body>

</head>
<div class="container" id="container">
	<div class="form-container sign-up-container">
    
		<form action="" method="post" enctype="multipart/form-data">
			<h1>Create Account</h1>
            <span class= "span1" style="margin-bottom: 10px;">Create your PTCI Instructor account and begin your academic journey today!</span>
            <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
			 <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">
             <input type="password" name="cpass" placeholder="Confirm your password" maxlength="20" required class="box">

             <span class ="profession">Your Profession 
             <select name="profession" class="box" required>
               <option value="" disabled selected>--Select your profession</option>
               <option value="Developer">Developer</option>
               <option value="Designer">Designer</option>
               <option value="Musician">Musician</option>
               <option value="Biologist">Biologist</option>
               <option value="Teacher">Teacher</option>
               <option value="Engineer">Engineer</option>
               <option value="Lawyer">Lawyer</option>
               <option value="Accountant">Accountant</option>
               <option value="Doctor">Doctor</option>
               <option value="Journalist">Journalist</option>
               <option value="Photographer">Photographer</option>
            </select>

            </span>
            
            <span  class="profile_pic">Please select your photo <span>*</span></span>
            <input type="file" name="image" accept="image/*" required class="box">
			<button style="margin-top: 5px;" type ="submit" name="submit_admin_signup" value="Register Now">Sign Up</button>
		</form>
	</div>
    
	<div class="form-container sign-in-container">
		<form action="" method="post" enctype="multipart/form-data" class="login">
			<h1>Welcome!</h1>
			<span>Log in using your email and password.</span>
			<input type="email" name="email" placeholder="Enter your Email" maxlength="100" required >
            <input type="password" name="pass" placeholder="Enter your Password" maxlength="20" required >
			<!-- <button><input type="submit" name="submit" value="login now"></button> -->
             <br>
            <button type="submit" name="submit_admin_login" value="login now" >Login Now</button>
            <p class="link">Are you an Student? <a href="../Login.php" style="color: blue;">Login here</a></p>
            
		</form> 
	</div>
	<div class="overlay-container">
   
		<div class="overlay">
              <div class="back_ptci-logo_right"></div>
              <div class="back_ptci-logo_left"></div>
			<div class="overlay-panel overlay-left">
				<h1>Welcome!</h1>
				<p>To stay connected with us, please log in with your personal info</p>
				<button class="ghost" id="signIn">Log In</button>
			</div>
			<div class="overlay-panel overlay-right">
            <h1 style="margin-top: 1cm; font-size: 24px;">Become an Instructor?</h1>
				<p>Join PTCI College as an instructor and inspire the next generation of learners by sharing your expertise, shaping futures, and making a lasting impact on education.</p>
				<button class="ghost" id="signUp">Get Started</button>
			</div>
		</div>
	</div>
</div>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>

<footer class="footer1">
   &copy; copyright @ <?= date('Y'); ?> by Palawan Technological College Inc. | all rights reserved!

</footer>

<!-- custom js file link  -->

   
</body>
</html>