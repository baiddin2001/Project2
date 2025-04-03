<?php

   // Database connection
   $db_name = 'mysql:host=localhost;dbname=course_db';
   $user_name = 'root';
   $user_password = '';

   try {
      $conn = new PDO($db_name, $user_name, $user_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
   }

   // Function to generate a unique ID
   function unique_id() {
      $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $rand = array();
      $length = strlen($str) - 1;
      for ($i = 0; $i < 20; $i++) {
          $n = mt_rand(0, $length);
          $rand[] = $str[$n];
      }
      return implode($rand);
   }

   // Function to get the user's strand
   function get_user_strand($conn, $user_id) {
      $query = $conn->prepare("SELECT strand FROM users WHERE id = ?");
      $query->execute([$user_id]);
      $result = $query->fetch(PDO::FETCH_ASSOC);
      return $result ? $result['strand'] : '';
   }

?>
