<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }

$target_dir = "uploads/";
if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $target_file = $target_dir . basename($_FILES['file']['name']);
    
    // VULNERABLE: No file type validation
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $_SESSION['success_msg'] = "File uploaded: <a href='$target_file'>$target_file</a>";
    } else {
        $_SESSION['error_msg'] = "Upload failed";
    }
}

header('Location: dashboard.php');
exit();
?>
