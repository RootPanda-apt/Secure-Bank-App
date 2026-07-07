CREATE DATABASE IF NOT EXISTS bankapi;
USE bankapi;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    email VARCHAR(100),
    account_no VARCHAR(20) UNIQUE,
    account_balance DECIMAL(15,2) DEFAULT 0.00,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    username VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_account VARCHAR(20),
    to_account VARCHAR(20),
    amount DECIMAL(15,2),
    description TEXT,
    status ENUM('pending','completed','failed') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO users (username, password, full_name, email, account_no, account_balance, role) VALUES
('admin', 'admin123', 'Bank Administrator', 'admin@securebank.local', 'ACC-1000001', 999999.99, 'admin'),
('john', 'password1', 'John Doe', 'john@email.com', 'ACC-1000002', 15000.00, 'user'),
('jane', 'password2', 'Jane Smith', 'jane@email.com', 'ACC-1000003', 25000.00, 'user'),
('bob', 'password3', 'Bob Wilson', 'bob@email.com', 'ACC-1000004', 5000.00, 'user');

INSERT IGNORE INTO transactions (from_account, to_account, amount, description) VALUES
('ACC-1000002', 'ACC-1000003', 500.00, 'Payment for dinner'),
('ACC-1000003', 'ACC-1000002', 200.00, 'Refund'),
('ACC-1000001', 'ACC-1000002', 1000.00, 'Bonus payment');

INSERT IGNORE INTO messages (user_id, username, message) VALUES
(1, 'admin', 'Welcome to SecureBank! Practice your pentesting skills here.'),
(2, 'john', 'Great platform for learning security testing!'),
(3, 'jane', 'Remember: Only test with proper authorization.');
