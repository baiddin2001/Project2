<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

   if(isset($_POST['submit'])){

      $id = unique_id();
      $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
      $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
      $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
      $playlist = filter_var($_POST['playlist'], FILTER_SANITIZE_STRING);

      // Thumbnail Upload
      $thumb = $_FILES['thumb']['name'];
      $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
      $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
      $rename_thumb = unique_id().'.'.$thumb_ext;
      $thumb_size = $_FILES['thumb']['size'];
      $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
      $thumb_folder = '../uploaded_files/'.$rename_thumb;

      // Video Upload
      $video = $_FILES['video']['name'];
      if(empty($video)) {
         $rename_video = 'none';
         $video_tmp_name = ''; 
         $video_folder = ''; 
      } else {
         $video = filter_var($video, FILTER_SANITIZE_STRING);
         $video_ext = pathinfo($video, PATHINFO_EXTENSION);
         $rename_video = unique_id().'.'.$video_ext;
         $video_tmp_name = $_FILES['video']['tmp_name'];
         $video_folder = '../uploaded_files/'.$rename_video;
      }

      // File Upload (PDF, Word, PPT, Excel)
      $file = $_FILES['file']['name'];
      $file_tmp_name = $_FILES['file']['tmp_name'];
      $rename_file = 'none';
      $file_folder = ''; 
      
      if (!empty($file)) {
          $file = filter_var($file, FILTER_SANITIZE_STRING);
          $file_ext = pathinfo($file, PATHINFO_EXTENSION);
          $rename_file = unique_id().'.'.$file_ext;
          $file_folder = '../uploaded_files/'.$rename_file;
      
          $allowed_extensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];
      
          if (in_array($file_ext, $allowed_extensions)) {
              move_uploaded_file($file_tmp_name, $file_folder);
          } else {
              $rename_file = 'none'; // Invalid file, set to 'none'
              $file_folder = ''; // No file to store
          }
      }

      if ($thumb_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         // Prepare SQL query dynamically
         $sql = "INSERT INTO content (id, tutor_id, playlist_id, title, description, video, thumb, status";
         $params = [$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status];

         if (!empty($rename_file)) {
            $sql .= ", file";
            $params[] = $rename_file;
         }

         $sql .= ") VALUES (" . implode(",", array_fill(0, count($params), "?")) . ")";

         $add_content = $conn->prepare($sql);
         $add_content->execute($params);

         move_uploaded_file($thumb_tmp_name, $thumb_folder);
         move_uploaded_file($video_tmp_name, $video_folder);

         $message[] = 'New course uploaded!';
      }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">Upload Content</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Select Status --</option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>

      <p>Title <span>*</span></p>
      <input type="text" name="title" maxlength="50" required placeholder="Enter Title" class="box">

      <p>Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"></textarea>

      <p>Resources Subject <span>*</span></p>
      <select name="playlist" class="box" required>
         <option value="" disabled selected>-- Select from Subjects --</option>
         <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         } else {
            echo '<option value="" disabled>No Subjects created yet!</option>';
         }
         ?>
      </select>

      <p>Please Input for the Cover Page <span>*</span></p>
      <input type="file" name="thumb" accept="image/*" required class="box">

      <p>Include a Video <span>(Optional)</span></p>
      <input type="file" name="video" accept="video/*" class="box">

      <p>Select Additional File (PDF, Word, PPT, Excel) <span>(Optional)</span></p>
      <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" class="box">

      <input type="submit" value="Upload Resource" name="submit" class="btn">
   </form>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
