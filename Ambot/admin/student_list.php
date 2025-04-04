
    
<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}

$tutor_id = $_SESSION['tutor_id'] ?? null; // Ensure tutor_id is set

// Fetch available class sections from the 'classes' table for each strand
$sections_humms = [];
$sections_ict = [];
$sections_tvl_he = [];

// Fetch HUMMS class sections
$get_humms_sections = $conn->prepare("SELECT class_name FROM classes WHERE strand = 'HUMMS'");
$get_humms_sections->execute();
$sections_humms = $get_humms_sections->fetchAll(PDO::FETCH_COLUMN);

// Fetch ICT class sections
$get_ict_sections = $conn->prepare("SELECT class_name FROM classes WHERE strand = 'ICT'");
$get_ict_sections->execute();
$sections_ict = $get_ict_sections->fetchAll(PDO::FETCH_COLUMN);

// Fetch TVL-HE class sections
$get_tvl_he_sections = $conn->prepare("SELECT class_name FROM classes WHERE strand = 'TVL-HE'");
$get_tvl_he_sections->execute();
$sections_tvl_he = $get_tvl_he_sections->fetchAll(PDO::FETCH_COLUMN);

// Handle student section update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_section'])) {
    $student_id = $_POST['student_id'];
    $class_section = $_POST['class_section'];

    // Get the student's strand
    $get_strand = $conn->prepare("SELECT strand FROM users WHERE id = ?");
    $get_strand->execute([$student_id]);
    $strand = $get_strand->fetchColumn();

    // Now get the corresponding class_id based on class_section AND strand
    $get_class_id = $conn->prepare("SELECT id FROM classes WHERE class_name = ? AND strand = ?");
    $get_class_id->execute([$class_section, $strand]);
    $class_id = $get_class_id->fetchColumn();

    // Update the user's class_section and class_id
    $update_section = $conn->prepare("UPDATE users SET class_section = ?, class_id = ? WHERE id = ?");
    $update_section->execute([$class_section, $class_id, $student_id]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}



// Handle student deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    $student_id = $_POST['delete_student'];
    $delete_student = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_student->execute([$student_id]);
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh page after deletion
    exit();
}

// Pagination logic (limit per page)
$limit = 10;
$humms_page = isset($_GET['humms_page']) ? (int)$_GET['humms_page'] : 1;
$ict_page = isset($_GET['ict_page']) ? (int)$_GET['ict_page'] : 1;
$tvl_he_page = isset($_GET['tvl_he_page']) ? (int)$_GET['tvl_he_page'] : 1;

// Offsets for pagination
$humms_offset = ($humms_page - 1) * $limit;
$ict_offset = ($ict_page - 1) * $limit;
$tvl_he_offset = ($tvl_he_page - 1) * $limit;

// Fetch HUMMS users
$select_humms_users = $conn->prepare("SELECT * FROM users WHERE strand = 'HUMMS' LIMIT $limit OFFSET $humms_offset");
$select_humms_users->execute();
$humms_users = $select_humms_users->fetchAll(PDO::FETCH_ASSOC);

// Fetch ICT users
$select_ict_users = $conn->prepare("SELECT * FROM users WHERE strand = 'ICT' LIMIT $limit OFFSET $ict_offset");
$select_ict_users->execute();
$ict_users = $select_ict_users->fetchAll(PDO::FETCH_ASSOC);

// Fetch TVL-HE users (including blank or NULL strand)
$select_tvl_he_users = $conn->prepare("SELECT * FROM users WHERE COALESCE(NULLIF(strand, ''), 'TVL-HE') = 'TVL-HE' LIMIT $limit OFFSET $tvl_he_offset");
$select_tvl_he_users->execute();
$tvl_he_users = $select_tvl_he_users->fetchAll(PDO::FETCH_ASSOC);

// Total count for HUMMS, ICT, and TVL-HE (including blank/NULL strand)
$total_humms_users = $conn->query("SELECT COUNT(*) FROM users WHERE strand = 'HUMMS'")->fetchColumn();
$total_ict_users = $conn->query("SELECT COUNT(*) FROM users WHERE strand = 'ICT'")->fetchColumn();
$total_tvl_he_users = $conn->query("SELECT COUNT(*) FROM users WHERE COALESCE(NULLIF(strand, ''), 'TVL-HE') = 'TVL-HE'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
   

    <style>

    .dashboard {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 2cm;
    }

    .box {
        background: linear-gradient(135deg, #4CAF50,rgb(54, 124, 56));
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        min-width: 250px;
        flex: 1;
        max-width: 300px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .box h3 {
        font-size: 28px;
        margin-bottom: 5px;
    }

    .box p {
        font-size: 14px;
        opacity: 0.9;
    }

    .box:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #4CAF50
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    .pagination a {
        padding: 8px 16px;
        margin: 0 5px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .pagination a:hover {
        background-color: #0056b3;
    }
    .student-photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .delete-btn {
        color: white;
        text-decoration: none;
        background: red;
        padding: 8px 12px;
        font-size: 10px;
        border-radius: 5px;
    }

    .delete-btn:hover {
        background: darkred;
    }

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

    .back-button {
    display: block !important; 
    position: fixed;
    top: 10px; 
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
    z-index: 2000;
    }
    .back-button:hover {
    background-color: #e0e0e0;
    }
    </style>
</head>
<body>

<header class="header">
    <span class="logo">PTCI ONLINE LEARNING MATERIAL SYSTEM</span>
</header>

<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>

<!-- Sidebar and Navigation -->
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
<br><br><br><br>
<section class="student-list">
    <!-- HUMMS Students -->
    <h2>HUMMS Students</h2>
    <table>
        <tr><th>Photo</th><th>Name</th><th>Email</th><th>Assign Class</th><th>Action</th></tr>
        <?php foreach ($humms_users as $user): ?>
            <tr>
                <td><img src="../uploaded_files/<?= htmlspecialchars($user['image']); ?>" alt="Student Photo" class="student-photo"></td>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="student_id" value="<?= $user['id']; ?>">
                        <select name="class_section" onchange="this.form.submit()">
                            <option value="">Select Section</option>
                            <?php foreach ($sections_humms as $section): ?>
                                <option value="<?= htmlspecialchars($section); ?>" <?= $user['class_section'] === $section ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($section); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="update_section" value="1">
                    </form>
                </td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        <button type="submit" name="delete_student" value="<?= $user['id']; ?>" class="delete-btn">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Pagination for HUMMS -->
    <div class="pagination">
        <?php for ($i = 1; $i <= ceil($total_humms_users / $limit); $i++): ?>
            <a href="?humms_page=<?= $i; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>

    <!-- ICT Students -->
    <h2>ICT Students</h2>
    <table>
    <tr><th>Photo</th><th>Name</th><th>Email</th><th>Assign Class</th><th>Action</th></tr>
        <?php foreach ($ict_users as $user): ?>
            <tr>
                <td><img src="../uploaded_files/<?= htmlspecialchars($user['image']); ?>" alt="Student Photo" class="student-photo"></td>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="student_id" value="<?= $user['id']; ?>">
                        <select name="class_section" onchange="this.form.submit()">
                            <option value="">Select Section</option>
                            <?php foreach ($sections_ict as $section): ?>
                                <option value="<?= htmlspecialchars($section); ?>" <?= $user['class_section'] === $section ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($section); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="update_section" value="1">
                    </form>
                </td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        <button type="submit" name="delete_student" value="<?= $user['id']; ?>" class="delete-btn">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Pagination for ICT -->
    <div class="pagination">
        <?php for ($i = 1; $i <= ceil($total_ict_users / $limit); $i++): ?>
            <a href="?ict_page=<?= $i; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>

    <!-- TVL-HE Students -->
    <h2>TVL-HE Students</h2>
    <table>
    <tr><th>Photo</th><th>Name</th><th>Email</th><th>Assign Class</th><th>Action</th></tr>
        <?php foreach ($tvl_he_users as $user): ?>
            <tr>
                <td><img src="../uploaded_files/<?= htmlspecialchars($user['image']); ?>" alt="Student Photo" class="student-photo"></td>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="student_id" value="<?= $user['id']; ?>">
                        <select name="class_section" onchange="this.form.submit()">
                            <option value="">Select Section</option>
                            <?php foreach ($sections_tvl_he as $section): ?>
                                <option value="<?= htmlspecialchars($section); ?>" <?= $user['class_section'] === $section ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($section); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="update_section" value="1">
                    </form>
                </td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        <button type="submit" name="delete_student" value="<?= $user['id']; ?>" class="delete-btn">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Pagination for TVL-HE -->
    <div class="pagination">
        <?php for ($i = 1; $i <= ceil($total_tvl_he_users / $limit); $i++): ?>
            <a href="?tvl_he_page=<?= $i; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>
</section>

</body>
</html>

