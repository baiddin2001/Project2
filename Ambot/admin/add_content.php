<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
   exit;
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
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   // Video Upload (No Size Limit)
   $video = $_FILES['video']['name'];
   if(empty($video)) {
      $rename_video = 'none';
   } else {
      $video = filter_var($video, FILTER_SANITIZE_STRING);
      $video_ext = pathinfo($video, PATHINFO_EXTENSION);
      $rename_video = unique_id().'.'.$video_ext;
      $video_tmp_name = $_FILES['video']['tmp_name'];
      $video_folder = '../uploaded_files/'.$rename_video;
   }

   // File Upload (PDF, Word, PPT, Excel)
   $file = $_FILES['file']['name'];
   $rename_file = 'none';
   if (!empty($file)) {
       $file = filter_var($file, FILTER_SANITIZE_STRING);
       $file_ext = pathinfo($file, PATHINFO_EXTENSION);
       $rename_file = unique_id().'.'.$file_ext;
       $file_tmp_name = $_FILES['file']['tmp_name'];
       $file_folder = '../uploaded_files/'.$rename_file;
       move_uploaded_file($file_tmp_name, $file_folder);
   }

   // Insert into database
   $sql = "INSERT INTO content (id, tutor_id, playlist_id, title, description, video, thumb, status, file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
   $params = [$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status, $rename_file];
   $add_content = $conn->prepare($sql);
   $add_content->execute($params);

   move_uploaded_file($thumb_tmp_name, $thumb_folder);
   if (!empty($video_tmp_name)) {
      move_uploaded_file($video_tmp_name, $video_folder);
   }

   $message[] = 'New course uploaded!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Upload Content</title>
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      .back-button {
         display: block !important; 
         position: absolute;
         top: 110px; 
         right: 20px;
         background-color: #66bb6a;
         color: black;
         font-weight: 500;
         padding: 8px 25px;
         border: 2px solid #ddd;
         border-radius: 5px;
         text-decoration: none;
         font-size: 18px;
         transition: background-color 0.3s ease;
         z-index: 1000;
      }
      .back-button:hover {
         background-color: #e0e0e0;
      }
   </style>
</head>
<body>
<a href="javascript:history.back()" class="back-button">
   <i class="fas fa-arrow-left"></i> Back
</a>
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
      <input type="text" name="title" required placeholder="Enter Title" class="box">
      <p>Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description"></textarea>
      <p>Resources Subject <span>*</span></p>
      <select name="playlist" class="box" required>
         <option value="" disabled selected>-- Select from Subjects --</option>
         <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php } ?>
      </select>
      <p>Cover Page <span>*</span></p>
      <input type="file" name="thumb" accept="image/*" required class="box">
      <p>Include a Video <span>(Optional)</span></p>
      <input type="file" name="video" accept="video/*" class="box">
      <p>Additional File (PDF, Word, PPT, Excel) <span>(Optional)</span></p>
      <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" class="box">
      <input type="submit" value="Upload Resource" name="submit" class="btn">
   </form>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
