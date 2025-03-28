<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Select Strand</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="strand-selection">
   <h1 class="heading">Select Strand</h1>

   <div class="box-container">
    <?php
    $fetch_strands = $conn->prepare("SELECT * FROM `strands`");
    $fetch_strands->execute();
    while ($strand = $fetch_strands->fetch(PDO::FETCH_ASSOC)) {
    ?>
        <a href="classes.php?strand=<?= htmlspecialchars($strand['name']); ?>" class="box">
            <h3><?= htmlspecialchars($strand['name']); ?></h3>
        </a>
    <?php } ?>
   </div>

   <a href="classes.php?strand=HUMMS" class="box">
   <h3>HUMMS</h3>
   </a>
   <a href="classes.php?strand=ICT" class="box">
   <h3>ICT</h3>
   </a>
   </div>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>

<?php
?>
