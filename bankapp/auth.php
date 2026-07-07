<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // VULNERABLE: SQL Injection in login
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $pdo->query($sql);
    
    if ($result && $row = $result->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['account_no'] = $row['account_no'];
        $_SESSION['balance'] = $row['balance'];
        $_SESSION['role'] = $row['role'];
        
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = 'Invalid username or password!';
        header('Location: index.php');
        exit();
    }
}
?>
