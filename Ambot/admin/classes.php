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

// Handle class deletion
if(isset($_GET['delete_class'])){
   $class_id = $_GET['delete_class'];

   // Check if the class exists and delete it
   $delete_class = $conn->prepare("DELETE FROM `classes` WHERE id = ? AND tutor_id = ?");
   $delete_class->execute([$class_id, $tutor_id]);

   // Redirect back to the same page to reflect changes
   header('location:classes.php?strand=' . $strand);
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
      .class-management .header-container {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 30px;
      }

      .class-management .heading {
         font-size: 28px;
         font-weight: bold;
         color: #333;
      }

      .create-class-form {
         display: flex;
         align-items: center;
         gap: 10px;
      }

      .create-class-form input[type="text"] {
         padding: 10px 20px;
         font-size: 16px;
         border: 1px solid #ddd;
         border-radius: 4px;
         max-width: 200px;
         width: 100%;
      }

      .create-class-form input[type="submit"] {
         padding: 10px 20px;
         font-size: 16px;
         background-color: #4CAF50;
         color: white;
         border: none;
         border-radius: 4px;
         cursor: pointer;
      }

      .create-class-form input[type="submit"]:hover {
         background-color: #45a049;
      }

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

      .delete-btn {
         background-color: #f44336;
         color: white;
         padding: 10px 15px;
         border-radius: 4px;
         cursor: pointer;
         font-size: 14px;
         text-decoration: none;
         display: inline-block;
         margin-top: 10px;
      }

      .delete-btn:hover {
         background-color: #e53935;
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
   <div class="header-container">
      <h1 class="heading">Classes for <?= $strand; ?> Strand</h1>
      <!-- Create New Class Form (aligned to the right) -->
      <form class="create-class-form" action="" method="post">
         <input type="text" name="class_name" placeholder="Enter class" required maxlength="50">
         <input type="submit" name="create_class" value="Create Class">
      </form>
   </div>

   <div class="box-container">
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
         <!-- Delete Button -->
         <a href="classes.php?strand=<?= $strand; ?>&delete_class=<?= $class_id; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this class?')">Delete</a>
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
