<!-- this is for the log in -->
<?php
session_start();
include_once 'components/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_login'])) {
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']); // Back to SHA1 for compatibility
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   if($select_user->rowCount() > 0){
       setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
       header('location:home.php');
       exit();
   } else {
       echo "Invalid email or password!";
   }
}
?>

<?php
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
}

// Signup logic but redirects to login page instead of auto-login
if (isset($_POST['submit_signup'])) {
   $id = unique_id();
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $strand = filter_var($_POST['strand'], FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;

   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);


   if($select_user->rowCount() > 0){
      $message[] = 'Email already taken!';
   } else {
      if($pass != $cpass){
         $message[] = 'Confirm password does not match!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image, strand) VALUES(?,?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $cpass, $rename, $strand]);
         move_uploaded_file($image_tmp_name, $image_folder);

         // ✅ Redirect to login page instead of auto-login
         header('location: login.php');
         exit();
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
   <title>Student Login</title>
   <link rel="stylesheet" href="css/login_register1.css">
</head>
<body>
<div class="container" id="container">
   <div class="form-container sign-up-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h1>Create Account</h1>
         <span class="span1">Create your account and begin your academic journey today!</span>
         <input type="text" name="name" placeholder="Enter your Name" maxlength="50" required>
         <input type="email" name="email" placeholder="Enter your Email" maxlength="100" required>
         <select name="strand" required>
            <option value="">Select Your Strand</option>
            <option value="ICT">ICT</option>
            <option value="HUMMS">HUMMS</option>
         </select>
         <input type="password" name="pass" placeholder="Enter your Password" maxlength="20" required>
         <input type="password" name="cpass" placeholder="Confirm your Password" maxlength="20" required>
         <span class="profile_pic">Please select your photo <span>*</span></span>
         <input type="file" name="image" accept="image/*" required>
         <button type="submit" name="submit_signup">Sign Up</button>
      </form>
   </div>
   <div class="form-container sign-in-container">
      <form action="" method="post" class="login">
         <h1>Welcome!</h1>
         <span>Log in using your email and password.</span>
         <input type="email" name="email" placeholder="Enter your Email" maxlength="100" required>
         <input type="password" name="pass" placeholder="Enter your Password" maxlength="20" required>
         <button type="submit" name="submit_login">Login Now</button>
         <p class="link">Are you an Instructor?  
         <a href="admin/Login.php" style="color: blue;">Login here</a>
         </p>
      </form> 
   </div>
   <div class="overlay-container">
      <div class="overlay">
         <div class="overlay-panel overlay-left">
            <h1>Welcome!</h1>
            <p>To keep connected with us please login with your personal info</p>
            <button class="ghost" id="signIn">Log In</button>
         </div>
         <div class="overlay-panel overlay-right">
            <h1>Hello, Student!</h1>
            <p>Don't have an account yet? Sign up now!</p>
            <button class="ghost" id="signUp">Sign Up</button>
         </div>
      </div>
   </div>
</div>

<style>
   select {
    background-color: #eee;
    border: none;
    padding: 12px 15px;
    margin: 8px 0;
    width: 100%;
    box-sizing: border-box;
    border-radius: 10px;
    font-size: 14px;
}
</style>

<script>
   document.getElementById('signUp').addEventListener('click', () => {
      document.getElementById('container').classList.add("right-panel-active");
   });
   document.getElementById('signIn').addEventListener('click', () => {
      document.getElementById('container').classList.remove("right-panel-active");
   });
</script>
<footer class="footer1">
   &copy; <?= date('Y'); ?> by Palawan Technological College Inc. | All rights reserved!
</footer>
</body>
</html>
