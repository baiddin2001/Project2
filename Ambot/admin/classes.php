<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Get the strand from the URL
if(isset($_GET['strand'])){
   $strand = $_GET['strand'];
} else {
   header('location:strand-selection.php'); // Redirect if no strand is selected
}

if(isset($_POST['create_class'])){
   $class_name = $_POST['class_name'];

   if(!empty($class_name)){
      // Insert new class with strand into the database
      $insert_class = $conn->prepare("INSERT INTO `classes` (tutor_id, class_name, strand) VALUES (?, ?, ?)");
      $insert_class->execute([$tutor_id, $class_name, $strand]);

      echo "<script>
         document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
               title: 'Class Created!',
               text: 'New class has been successfully created!',
               icon: 'success',
               confirmButtonText: 'OK'
            }).then(() => {
               window.location.href = 'classes.php?strand=$strand';
            });
         });
      </script>";
   } else {
      echo "<script>
         document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
               title: 'Error!',
               text: 'Please enter a class name!',
               icon: 'error',
               confirmButtonText: 'OK'
            });
         });
      </script>";
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Classes - <?= $strand; ?> </title>
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      .class-management .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
         gap: 20px;
         margin-top: 20px;
      }

      .class-management .box {
         background: #fff;
         padding: 20px;
         text-align: center;
         border-radius: 8px;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         transition: all 0.3s ease;
         font-size: 16px; /* Ensures readability */
         line-height: 1.5; /* Line spacing for better readability */
      }

      .class-management .box:hover {
         transform: translateY(-5px);
         box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
      }

      .class-management .box .title {
         font-size: 20px; /* Larger font size for titles */
         font-weight: bold;
         margin-bottom: 15px;
         color: #333;
      }

      .class-management .box .btn {
         background-color: #4CAF50;
         color: white;
         padding: 12px 25px;
         border: none;
         border-radius: 4px;
         cursor: pointer;
         text-decoration: none;
         font-size: 16px; /* Larger text for the button */
      }

      .class-management .box .btn:hover {
         background-color: #45a049;
      }

      .class-management .box form {
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .class-management .box input[type="text"] {
         padding: 12px;
         font-size: 16px;
         margin-right: 10px;
         border: 1px solid #ddd;
         border-radius: 4px;
         width: 60%; /* Ensures input takes up a reasonable width */
      }

      .class-management .box input[type="submit"] {
         padding: 12px 25px;
         font-size: 16px;
         background-color: #4CAF50;
         color: white;
         border: none;
         border-radius: 4px;
         cursor: pointer;
      }

      .class-management .box input[type="submit"]:hover {
         background-color: #45a049;
      }

      .empty {
         text-align: center;
         color: #888;
         font-size: 18px;
         margin-top: 20px;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="class-management">
   <h1 class="heading">Classes for <?= $strand; ?> Strand</h1>

   <div class="box-container">
      <!-- Form to create new class -->
      <div class="box" style="text-align: center;">
         <h3 class="title">Create New Class</h3>
         <form action="" method="post">
            <input type="text" name="class_name" placeholder="Enter class" required maxlength="50">
            <input type="submit" name="create_class" value="Create Class" class="btn">
         </form>
      </div>

      <?php
         // Fetch all the classes created by the tutor for the selected strand
         $select_classes = $conn->prepare("SELECT * FROM `classes` WHERE tutor_id = ? AND strand = ? ORDER BY class_name ASC");
         $select_classes->execute([$tutor_id, $strand]);

         if($select_classes->rowCount() > 0){
            while($fetch_class = $select_classes->fetch(PDO::FETCH_ASSOC)){
               $class_id = $fetch_class['id'];
      ?>
      <div class="box">
         <h3 class="title"><?= $fetch_class['class_name']; ?></h3>
         <a href="playlists.php?strand=<?= $strand; ?>&class_id=<?= $class_id; ?>" class="btn">View Subjects</a>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No classes created yet!</p>';
         }
      ?>
   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
