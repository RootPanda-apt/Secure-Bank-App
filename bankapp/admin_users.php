<?php
// Forbidden by default - must use bypass techniques
http_response_code(403);
header('HTTP/1.0 403 Forbidden');
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<head><title>403 Forbidden - Admin Access Denied</title></head>
<body style="background:#1a1a2e;color:#e94560;font-family:Arial;text-align:center;padding:100px;">
    <h1>403 Forbidden</h1>
    <p>You do not have permission to access the admin panel.</p>
    <p style="color:#666;font-size:14px;">Admin privileges required.</p>
    <hr style="width:200px;border-color:#0f3460;">
    <p style="color:#666;font-size:12px;">BankAPI Security Module - Admin Protection</p>
</body>
</html>
<?php
exit();
?>
