<?php
include 'config.php';

// Debug: Clear any previous output
while (ob_get_level()) ob_end_clean();

header('Content-Type: text/html');

// Check various bypass techniques
$bypassed = false;
$bypass_method = '';

// Technique 1: X-Forwarded-For header
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == '127.0.0.1') {
    $bypassed = true;
    $bypass_method = 'X-Forwarded-For (localhost)';
}

// Technique 2: X-Admin header
if (isset($_SERVER['HTTP_X_ADMIN']) && $_SERVER['HTTP_X_ADMIN'] == 'true') {
    $bypassed = true;
    $bypass_method = 'X-Admin header';
}

// Technique 3: X-Role header
if (isset($_SERVER['HTTP_X_ROLE']) && $_SERVER['HTTP_X_ROLE'] == 'admin') {
    $bypassed = true;
    $bypass_method = 'X-Role header';
}

// Technique 4: Authorization header with admin token
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $auth = $_SERVER['HTTP_AUTHORIZATION'];
    if ($auth == 'Bearer admin_token_2024' || $auth == 'admin:admin123') {
        $bypassed = true;
        $bypass_method = 'Authorization header with admin token';
    }
}

// Technique 5: Custom cookie
if (isset($_COOKIE['admin_access']) && $_COOKIE['admin_access'] == 'granted') {
    $bypassed = true;
    $bypass_method = 'Admin access cookie';
}

// Technique 6: URL parameter bypass
if (isset($_GET['admin']) && $_GET['admin'] == 'true') {
    $bypassed = true;
    $bypass_method = 'URL parameter admin=true';
}

// Technique 7: Referer header from admin area - STRICTER check
if (isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
    if (strpos($ref, '/admin/') !== false) {
        $bypassed = true;
        $bypass_method = 'Referer from /admin/ path';
    }
}

// Technique 8: X-Original-URL header
if (isset($_SERVER['HTTP_X_ORIGINAL_URL']) && strpos($_SERVER['HTTP_X_ORIGINAL_URL'], 'admin') !== false) {
    $bypassed = true;
    $bypass_method = 'X-Original-URL header';
}

// Technique 9: X-Rewrite-URL header
if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
    $bypassed = true;
    $bypass_method = 'X-Rewrite-URL header';
}

// Technique 10: Token in query string
if (isset($_GET['token']) && $_GET['token'] == 'supersecretadmintoken') {
    $bypassed = true;
    $bypass_method = 'Secret admin token in URL';
}

// Technique 11: User-Agent bypass
if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'InternalBot') !== false) {
    $bypassed = true;
    $bypass_method = 'User-Agent InternalBot';
}

// Technique 12: Method override
if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) && $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] == 'ADMIN') {
    $bypassed = true;
    $bypass_method = 'HTTP Method Override: ADMIN';
}

if ($bypassed) {
    // Test DB connection first
    try {
        $stmt = $pdo->query("SELECT id, username, password, role FROM users");
        $users = $stmt->fetchAll();
    } catch (Exception $e) {
        echo "<h2>Database Error: " . $e->getMessage() . "</h2>";
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Panel - All Users</title>
        <style>
            body { font-family: Arial; background: #f0f2f5; padding: 20px; }
            .card { background: white; border-radius: 15px; padding: 30px; max-width: 1000px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            h2 { color: #0c2461; }
            .bypass-note { background: #d4edda; border: 1px solid #28a745; padding: 12px; border-radius: 5px; margin-bottom: 20px; color: #155724; font-weight: bold; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background: #0c2461; color: white; padding: 12px; text-align: left; }
            td { padding: 12px; border-bottom: 1px solid #f0f0f0; }
            tr:hover { background: #f8f9fa; }
            .admin { color: #e74c3c; font-weight: bold; }
            .user { color: #27ae60; }
        </style>
    </head>
    <body>
        <div class="card">
            <div class="bypass-note">
                🔓 Access Granted via: <?php echo htmlspecialchars($bypass_method); ?>
            </div>
            <h2>👥 Admin Panel - All Users</h2>
            <?php if (count($users) == 0): ?>
                <p style="color:#e74c3c;">No users found in database.</p>
            <?php else: ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <?php $role_class = ($user['role'] == 'admin') ? 'admin' : 'user'; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td style="color: #999; font-family: monospace;"><?php echo htmlspecialchars($user['password']); ?></td>
                        <td class="<?php echo $role_class; ?>"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
            <p style="margin-top:20px;color:#666;font-size:12px;">
                <a href="dashboard.php">← Back to Dashboard</a>
            </p>
        </div>
    </body>
    </html>
    <?php
} else {
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>403 Forbidden</title></head>
    <body style="background:#1a1a2e;color:#e94560;font-family:Arial;text-align:center;padding:100px;">
        <h1>403 Forbidden</h1>
        <p>No valid bypass method detected.</p>
        <p style="color:#666;font-size:12px;">Try different access techniques.</p>
    </body>
    </html>
    <?php
}
?>
