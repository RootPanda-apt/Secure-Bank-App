<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }

$user_id = $_GET['user_id'] ?? $_SESSION['user_id'];

// VULNERABLE: IDOR - no access control, anyone can view anyone
$query = "SELECT * FROM users WHERE id = $user_id";
$stmt = $pdo->query($query);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head><title>View Account - SecureBank</title>
<style>
    body { font-family: Arial; background: #f0f2f5; padding: 20px; }
    .card { background: white; border-radius: 15px; padding: 30px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #0c2461; }
    .field { margin-bottom: 15px; }
    .field label { font-weight: 600; color: #666; display: block; font-size: 13px; }
    .field .value { font-size: 18px; color: #333; }
    .admin-badge { background: #e74c3c; color: white; padding: 3px 10px; border-radius: 10px; font-size: 12px; }
    a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #1e3799; color: white; text-decoration: none; border-radius: 8px; }
</style>
</head>
<body>
    <div class="card">
        <?php if ($target): ?>
        <h2>👤 Account Details</h2>
        <p><small>Viewing User ID: <?php echo $user_id; ?></small></p>
        <hr style="margin:15px 0;">
        <div class="field">
            <label>Full Name</label>
            <div class="value"><?php echo $target['full_name']; ?></div>
        </div>
        <div class="field">
            <label>Username</label>
            <div class="value"><?php echo $target['username']; ?></div>
        </div>
        <div class="field">
            <label>Email</label>
            <div class="value"><?php echo $target['email']; ?></div>
        </div>
        <div class="field">
            <label>Phone</label>
            <div class="value"><?php echo $target['phone']; ?></div>
        </div>
        <div class="field">
            <label>Account Number</label>
            <div class="value"><?php echo $target['account_no']; ?></div>
        </div>
        <div class="field">
            <label>Balance</label>
            <div class="value" style="font-size:24px;font-weight:bold;color:#27ae60;">$<?php echo number_format($target['balance'], 2); ?></div>
        </div>
        <div class="field">
            <label>Role</label>
            <div class="value"><?php echo ucfirst($target['role']); ?> <?php if($target['role']=='admin') echo '<span class="admin-badge">ADMIN</span>'; ?></div>
        </div>
        <?php else: ?>
        <h2>❌ User Not Found</h2>
        <p>No user exists with ID: <?php echo $user_id; ?></p>
        <?php endif; ?>
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>
