<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to_account = $_POST['to_account'];
    $amount = floatval($_POST['amount']);
    $description = $_POST['description'];
    
    // Check if recipient exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE account_no = ?");
    $stmt->execute([$to_account]);
    $recipient = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($recipient && $amount > 0 && $amount <= $user['balance']) {
        try {
            $pdo->beginTransaction();
            
            // Update balances
            $pdo->exec("UPDATE users SET balance = balance - $amount WHERE id = $user_id");
            $pdo->exec("UPDATE users SET balance = balance + $amount WHERE account_no = '$to_account'");
            
            // Log transaction
            $txn_id = bin2hex(random_bytes(16));
            $pdo->exec("INSERT INTO transactions (txn_id, from_account, to_account, from_name, to_name, amount, type, description, status) 
                       VALUES ('$txn_id', '{$user['account_no']}', '$to_account', '{$user['full_name']}', '{$recipient['full_name']}', $amount, 'transfer', '$description', 'completed')");
            
            $pdo->commit();
            
            // Update session balance
            $_SESSION['balance'] = $user['balance'] - $amount;
            $_SESSION['success_msg'] = "Transfer of $$amount sent successfully!";
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error_msg'] = "Transfer failed: " . $e->getMessage();
        }
    } else {
        if (!$recipient) $_SESSION['error_msg'] = "Account not found: $to_account";
        elseif ($amount <= 0) $_SESSION['error_msg'] = "Invalid amount";
        else $_SESSION['error_msg'] = "Insufficient funds";
    }
    
    header('Location: dashboard.php');
    exit();
}
?>
