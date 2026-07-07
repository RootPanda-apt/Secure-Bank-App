<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    
    // STRIP <script> tags completely
    $message = preg_replace('/<script[^>]*>.*?<\/script>/si', '', $message);
    $message = preg_replace('/<script[^>]*\/>/si', '', $message);
    
    // Also strip event handlers that aren't onload
    // Block onclick, onmouseover, onerror, onfocus, etc. - ONLY allow onload
    $message = preg_replace('/\son\w+(?<!onload)\s*=\s*"[^"]*"/si', '', $message);
    $message = preg_replace('/\son\w+(?<!onload)\s*=\s*\'[^\']*\'/si', '', $message);
    
    // Strip other dangerous tags
    $message = strip_tags($message, '<img><b><i><u><br><p><div><span><h1><h2><h3>');
    
    // VULNERABLE: Still allows <img onload> XSS
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, username, message) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $username, $message]);
}

header('Location: dashboard.php');
exit();
?>
