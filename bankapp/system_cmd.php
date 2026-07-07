<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit(); }
?>
<!DOCTYPE html>
<html>
<head><title>System Tools - SecureBank</title>
<style>
    body { font-family: Arial; background: #1a1a2e; color: #fff; padding: 20px; }
    .card { background: #16213e; border-radius: 15px; padding: 30px; max-width: 800px; margin: 0 auto; }
    h2 { color: #e94560; }
    input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #0f3460; border-radius: 8px; background: #0f3460; color: #fff; font-family: monospace; }
    button { padding: 12px 25px; background: #e94560; color: #fff; border: none; border-radius: 8px; cursor: pointer; }
    pre { background: #0f0f23; padding: 15px; border-radius: 8px; margin-top: 15px; overflow-x: auto; border: 1px solid #0f3460; }
    a { color: #4ecca3; text-decoration: none; display: block; margin-bottom: 20px; }
    .info { color: #888; font-size: 13px; margin-bottom: 15px; }
</style>
</head>
<body>
    <div class="card">
        <a href="dashboard.php">← Back to Dashboard</a>
        <h2>⚙️ System Command Tool</h2>
        <div class="info">Execute system commands for diagnostics. Enter a command below.</div>
        <form method="POST">
            <input type="text" name="cmd" placeholder="Enter command (e.g., ls -la, id, whoami)" value="<?php echo $_POST['cmd'] ?? 'id'; ?>">
            <button type="submit">Execute</button>
        </form>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['cmd'])): ?>
        <h3 style="color:#4ecca3;margin-top:20px;">Output:</h3>
        <pre><?php
            // VULNERABLE: Command Injection
            $cmd = $_POST['cmd'];
            echo shell_exec($cmd);
        ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>
