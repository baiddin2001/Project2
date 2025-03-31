<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   header('location:login.php');
   exit();
}

// Fetch assigned strands for the logged-in teacher
$fetch_strands = $conn->prepare("
    SELECT strands.* 
    FROM `strands`
    JOIN `strand_assignments` ON strands.id = strand_assignments.strand_id
    WHERE strand_assignments.teacher_id = ?
");
$fetch_strands->execute([$tutor_id]);
$assigned_strands = $fetch_strands->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Select Strand</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="strand-selection">
   <h1 class="heading">Select Strand</h1>

   <?php if(empty($assigned_strands)) { ?>
       <p class="message">No strand assigned. Please contact the admin.</p>
   <?php } else { ?>
       <div class="box-container">
           <?php foreach ($assigned_strands as $strand) { ?>
               <a href="classes.php?strand=<?= htmlspecialchars($strand['name']); ?>" class="box">
                   <h3><?= htmlspecialchars($strand['name']); ?></h3>
               </a>
           <?php } ?>
       </div>
   <?php } ?>
</section>

</body>
</html>


   <?php include '../components/footer.php'; ?>

   <script src="../js/admin_script.js"></script>

   </body>
   </html>

   <?php
   ?>
