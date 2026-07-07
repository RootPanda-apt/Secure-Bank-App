<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>SecureBank - Online Banking</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: linear-gradient(135deg, #0c2461 0%, #1e3799 50%, #0c2461 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo-icon {
            font-size: 60px;
            display: block;
        }
        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: #1e3799;
            letter-spacing: 1px;
        }
        .logo-sub {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
            text-align: center;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .input-group input:focus {
            border-color: #1e3799;
            outline: none;
        }
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1e3799, #0c2461);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30,55,153,0.4);
        }
        .error-msg {
            background: #ffe0e0;
            color: #d63031;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        .info-msg {
            background: #e0f0ff;
            color: #1e3799;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 13px;
        }
        .login-footer a {
            color: #1e3799;
            text-decoration: none;
        }
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <span class="logo-icon">🏦</span>
            <div class="logo-text">SecureBank</div>
        </div>
        <div class="logo-sub">Your Trusted Banking Partner</div>
        
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-msg">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="info-msg">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        
        <form method="POST" action="auth.php">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-login">Sign In to Your Account</button>
        </form>
        
        <div class="login-footer">
            <a href="#">Forgot Password?</a> &bull; <a href="#">Register</a>
        </div>
    </div>
</body>
</html>
