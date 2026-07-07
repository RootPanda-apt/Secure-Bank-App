<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }

$stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC LIMIT 50");
$txns = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><title>All Transactions - Admin</title>
<style>
    body { font-family: Arial; background: #f0f2f5; padding: 20px; }
    .card { background: white; border-radius: 15px; padding: 30px; max-width: 1200px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #0c2461; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
    th { background: #0c2461; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #f0f0f0; }
    tr:hover { background: #f8f9fa; }
    a { display: inline-block; margin-bottom: 15px; padding: 10px 20px; background: #1e3799; color: white; text-decoration: none; border-radius: 8px; }
    .credit { color: #27ae60; }
    .debit { color: #e74c3c; }
</style>
</head>
<body>
    <div class="card">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>💳 All Transactions (Admin View)</h2>
        <p style="color:#666;margin-bottom:20px;">Showing last 50 transactions</p>
        <table>
            <tr>
                <th>Date</th>
                <th>Transaction ID</th>
                <th>From</th>
                <th>To</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            <?php foreach ($txns as $t): ?>
            <tr>
                <td><?php echo date('M d, H:i', strtotime($t['created_at'])); ?></td>
                <td style="font-family:monospace;font-size:11px;"><?php echo substr($t['txn_id'], 0, 12); ?>...</td>
                <td><?php echo $t['from_name']; ?><br><small><?php echo $t['from_account']; ?></small></td>
                <td><?php echo $t['to_name']; ?><br><small><?php echo $t['to_account']; ?></small></td>
                <td class="credit">$<?php echo number_format($t['amount'], 2); ?></td>
                <td><?php echo ucfirst($t['type']); ?></td>
                <td><?php echo $t['description']; ?></td>
                <td><?php echo ucfirst($t['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
