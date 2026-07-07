<?php
header('Content-Type: application/json');

// Check various bypass techniques
$bypassed = false;
$method = '';

// Technique 1: X-Forwarded-For header bypass
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == '127.0.0.1') {
    $bypassed = true;
    $method = 'X-Forwarded-For header (localhost)';
}

// Technique 2: X-Real-IP header bypass
if (isset($_SERVER['HTTP_X_REAL_IP']) && $_SERVER['HTTP_X_REAL_IP'] == '127.0.0.1') {
    $bypassed = true;
    $method = 'X-Real-IP header (localhost)';
}

// Technique 3: Referer header spoofing
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'admin') !== false) {
    $bypassed = true;
    $method = 'Referer header contains admin';
}

// Technique 4: Custom header bypass
if (isset($_SERVER['HTTP_X_ADMIN']) && $_SERVER['HTTP_X_ADMIN'] == 'true') {
    $bypassed = true;
    $method = 'X-Admin header';
}

// Technique 5: Cookie bypass
if (isset($_COOKIE['admin_token']) && $_COOKIE['admin_token'] == 'bypass123') {
    $bypassed = true;
    $method = 'Admin cookie';
}

// Technique 6: URL parameter bypass
if (isset($_GET['debug']) && $_GET['debug'] == 'true') {
    $bypassed = true;
    $method = 'URL parameter debug=true';
}

// Technique 7: HTTP Method override
if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) && $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] == 'DEBUG') {
    $bypassed = true;
    $method = 'HTTP method override';
}

// Technique 8: Authorization header
if (isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION'] == 'Bearer bypass_token_123') {
    $bypassed = true;
    $method = 'Authorization header';
}

// Technique 9: User-Agent bypass
if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'AdminBot') !== false) {
    $bypassed = true;
    $method = 'User-Agent AdminBot';
}

// Technique 10: Query string bypass
if (isset($_GET['secret']) && $_GET['secret'] == 'debug2024') {
    $bypassed = true;
    $method = 'Secret query parameter';
}

if ($bypassed) {
    echo json_encode([
        "bypass_method" => $method,
        "application" => "BankAPI Attack Lab v1.0",
        "environment" => "DEVELOPMENT",
        "debug_mode" => true,
        "php_version" => phpversion(),
        "server" => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache',
        "server_ip" => $_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname()),
        "document_root" => $_SERVER['DOCUMENT_ROOT'],
        "config_file" => "/var/www/html/bankapp/config.php",
        "database" => [
            "host" => "localhost",
            "name" => "securebank",
            "user" => "bankapp",
            "password" => "BankPass123"
        ],
        "api_keys" => [
            "admin" => "sk_live_admin_1234567890abcdef",
            "john" => "sk_live_john_abcdef1234567890"
        ],
        "all_users" => [
            ["id" => 1, "username" => "admin", "password" => "admin123", "role" => "admin", "balance" => 99999999.99],
            ["id" => 2, "username" => "john", "password" => "password1", "role" => "user", "balance" => 15000.00],
            ["id" => 3, "username" => "jane", "password" => "password2", "role" => "user", "balance" => 25000.00],
            ["id" => 4, "username" => "bob", "password" => "password3", "role" => "user", "balance" => 5000.00]
        ]
    ], JSON_PRETTY_PRINT);
} else {
    http_response_code(403);
    echo json_encode(["error" => "Access denied. No valid bypass method detected."]);
}
?>
