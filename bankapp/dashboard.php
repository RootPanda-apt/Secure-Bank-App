<?php 
include 'config.php';
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit(); 
}

// Get user info
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get recent transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE from_account = ? OR to_account = ? ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$user['account_no'], $user['account_no']]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>SecureBank - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; min-height: 100vh; }
        
        /* Top Navigation */
        .topnav {
            background: linear-gradient(135deg, #0c2461, #1e3799);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .topnav .brand {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .topnav .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .topnav .user-menu .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4a69bd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
        .topnav .user-menu .user-name {
            font-weight: 600;
        }
        .topnav .user-menu .role-badge {
            background: <?php echo $user['role'] == 'admin' ? '#e74c3c' : '#4a69bd'; ?>;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #1e3799, #4a69bd);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(30,55,153,0.2);
        }
        .welcome-card h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .welcome-card .greeting {
            opacity: 0.9;
            font-size: 16px;
        }
        .account_balance-section {
            margin-top: 20px;
        }
        .account_balance-section .label {
            font-size: 14px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .account_balance-section .amount {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 2px;
        }
        .account_balance-section .account-info {
            display: flex;
            gap: 30px;
            margin-top: 10px;
            font-size: 14px;
            opacity: 0.9;
        }
        
        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card h3 {
            color: #0c2461;
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card .icon {
            font-size: 24px;
        }
        
        /* Form Elements */
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
            font-size: 13px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #1e3799;
            outline: none;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 60px;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #1e3799, #0c2461);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: transform 0.2s;
            width: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-success {
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        
        /* Table */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        .table-container h3 {
            color: #0c2461;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }
        table tr:hover {
            background: #f8f9fa;
        }
        .credit { color: #27ae60; font-weight: 600; }
        .debit { color: #e74c3c; font-weight: 600; }
        .status-completed { color: #27ae60; }
        .status-pending { color: #f39c12; }
        
        /* Alert / Message */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        /* Admin Section */
        .admin-section {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .admin-section h3 {
            color: #856404;
        }
        
        /* Guestbook */
        .guestbook-entry {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .guestbook-entry:last-child {
            border-bottom: none;
        }
        .guestbook-entry .gb-user {
            font-weight: 600;
            color: #1e3799;
            font-size: 13px;
        }
        .guestbook-entry .gb-time {
            color: #999;
            font-size: 12px;
        }
        .guestbook-entry .gb-msg {
            margin-top: 5px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
            .topnav { flex-direction: column; gap: 10px; }
            .account_balance-section .amount { font-size: 32px; }
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <div class="topnav">
        <div class="brand">🏦 SecureBank</div>
        <div class="user-menu">
            <div class="user-avatar"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
            <div>
                <div class="user-name"><?php echo $user['full_name']; ?></div>
                <div class="role-badge"><?php echo ucfirst($user['role']); ?></div>
            </div>
            <form action="logout.php" method="POST" style="display:inline;">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <!-- Messages -->
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></div>
        <?php endif; ?>
        
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h1>Welcome back, <?php echo $user['full_name']; ?>!</h1>
            <div class="greeting">Here's your account overview</div>
            <div class="account_balance-section">
                <div class="label">Available Balance</div>
                <div class="amount">$<?php echo number_format($user['account_balance'], 2); ?></div>
                <div class="account-info">
                    <span>Account: <?php echo $user['account_no']; ?></span>
                    <span>Email: <?php echo $user['email']; ?></span>
                    <span>Member since: <?php echo date('M Y', strtotime($user['created_at'])); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Cards Grid -->
        <div class="grid">
            <!-- Transfer Money -->
            <div class="card">
                <h3><span class="icon">💸</span> Send Money</h3>
                <form method="POST" action="transfer.php">
                    <div class="form-group">
                        <label>Recipient Account</label>
                        <input type="text" name="to_account" placeholder="e.g., SB-100002" required>
                    </div>
                    <div class="form-group">
                        <label>Amount ($)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" placeholder="What's this for?">
                    </div>
                    <button type="submit" class="btn-primary">Send Transfer</button>
                </form>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <h3><span class="icon">⚡</span> Quick Actions</h3>
                <div class="form-group">
                    <label>View Another Account (Admin Only)</label>
                    <form method="GET" action="view_account.php" style="display:flex;gap:10px;">
                        <input type="text" name="user_id" placeholder="Enter User ID" style="flex:1;">
                        <button type="submit" class="btn-primary" style="width:auto;padding:12px 20px;">View</button>
                    </form>
                </div>
                <hr style="margin:15px 0;border-color:#f0f0f0;">
                <div class="form-group">
                    <label>Quick Debug</label>
                    <a href="debug_bypass.php" target="_blank"><button type="button" class="btn-warning" style="width:100%;">System Info</button></a>
                </div>
                <hr style="margin:15px 0;border-color:#f0f0f0;">
                <div class="form-group">
                    <label>Upload Profile Photo</label>
                    <form method="POST" action="upload.php" enctype="multipart/form-data">
                        <input type="file" name="file" style="margin-bottom:10px;">
                        <button type="submit" class="btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Admin Section (Visible to All - Broken Access Control) -->
        <?php if ($user['role'] == 'admin'): ?>
        <div class="admin-section">
            <h3>🔐 Admin Control Panel</h3>
            <p style="margin-bottom:15px;">You have admin privileges. Use the tools below to manage the system.</p>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="admin_users_bypass.php"><button class="btn-danger">👥 View All Users</button></a>
                <a href="admin_transactions.php"><button class="btn-warning">💳 All Transactions</button></a>
                <a href="system_cmd.php"><button class="btn-danger">⚙️ System Command</button></a>
                <a href="debug_bypass.php"><button class="btn-success">ℹ️ Debug Info</button></a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Recent Transactions -->
        <div class="table-container">
            <h3>📋 Recent Transactions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $txn): ?>
                    <tr>
                        <td><?php echo date('M d, H:i', strtotime($txn['created_at'])); ?></td>
                        <td><?php echo $txn['description']; ?></td>
                        <td><?php echo $txn['from_name']; ?><br><small style="color:#999;"><?php echo $txn['from_account']; ?></small></td>
                        <td><?php echo $txn['to_name']; ?><br><small style="color:#999;"><?php echo $txn['to_account']; ?></small></td>
                        <td class="<?php echo $txn['from_account'] == $user['account_no'] ? 'debit' : 'credit'; ?>">
                            <?php echo $txn['from_account'] == $user['account_no'] ? '-' : '+'; ?>$<?php echo number_format($txn['amount'], 2); ?>
                        </td>
                        <td class="status-<?php echo $txn['status']; ?>"><?php echo ucfirst($txn['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($transactions)): ?>
                    <tr><td colspan="6" style="text-align:center;color:#999;padding:30px;">No transactions found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Guestbook (XSS Vulnerable) -->
        <div class="table-container">
            <h3>📝 Guestbook</h3>
            <form method="POST" action="guestbook.php" style="display:flex;gap:10px;margin-bottom:20px;">
                <input type="text" name="message" placeholder="Leave a message..." style="flex:1;padding:12px;border:2px solid #e0e0e0;border-radius:8px;">
                <button type="submit" class="btn-primary" style="width:auto;padding:12px 25px;">Post</button>
            </form>
            <div id="guestbook-entries">
                <?php
                $msg_stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 20");
                while ($msg = $msg_stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="guestbook-entry">
                    <div>
                        <span class="gb-user"><?php echo $msg['username']; ?></span>
                        <span class="gb-time"><?php echo date('M d, H:i', strtotime($msg['created_at'])); ?></span>
                    </div>
                    <div class="gb-msg"><?php echo $msg['message']; ?></div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>
