<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Strands</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<header class="header">
    <span class="logo">PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
</header>

<div class="side-bar">
    <nav class="navbar">
        <a href="admin_strands.php">
            <i class="fas fa-layer-group"></i><span>Manage Strands</span>
        </a>
        <a href="admin_subjects.php">
            <i class="fas fa-book"></i><span>Subjects</span>
        </a>
        <a href="student_teacher.php">
            <i class="fas fa-users"></i><span>Students/Teachers</span>
        </a>
        <a href="../admin_login.php" onclick="return confirm('Logout from this website?');">
            <i class="fas fa-right-from-bracket"></i><span>LOGOUT</span>
        </a>
    </nav>
</div>

<section class="strand-selection" style="margin-left: 8cm;">
   <h1 class="heading">Please Select</h1>

   <div class="box-container">
      <a href="teacher_list.php" class="box">
         <h3>Teacher list</h3>
      </a>
      <a href="student_list.php" class="box">
         <h3>Student List</h3>
      </a>
   </div>
</section>



<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .manage-strands { width: 80%; max-width: 900px; margin: auto; text-align: center; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
    h1 { font-size: 28px; }
    .message { color: green; font-weight: bold; font-size: 18px; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; font-size: 18px; }
    th, td { padding: 14px; border: 1px solid #ddd; }
    th { background-color: #f4f4f4; font-size: 20px; }
    .delete-btn { color: white; text-decoration: none; background: red; padding: 8px 12px; font-size: 16px; border-radius: 5px; }
    .delete-btn:hover { background: darkred; }
</style>

    <style>
       .header {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            padding: 15px 0;
            background-color: #f4f4f4;
            width: 100%;
            position: fixed;
            top: 0;
            left: 4cm;
            z-index: 1000;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .manage-strands {
            width: 80%;
            max-width: 900px;
            margin: 80px auto 20px; /* Increased top margin to 80px */
            text-align: center;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        
        h1 {
            font-size: 28px;
        }

        .back-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        form input, form button {
            font-size: 18px;
            padding: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 18px;
        }
        
        th, td {
            padding: 14px;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #f4f4f4;
            font-size: 20px;
        }
        
        .message {
            color: green;
            font-weight: bold;
            font-size: 18px;
        }
        
        .delete-btn {
            color: white;
            text-decoration: none;
            background: red;
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 5px;
        }
        
        .delete-btn:hover {
            background: darkred;
        }
    </style>

    <script src="../js/admin_script.js"></script>

    </body>
    </html>
