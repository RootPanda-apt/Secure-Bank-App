#!/bin/bash
set -e

echo "========================================"
echo "  Starting SecureBank Lab"
echo "========================================"

# Initialize MariaDB if needed
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "[1/4] Initializing MariaDB..."
    mysql_install_db --user=mysql --datadir=/var/lib/mysql > /dev/null 2>&1
fi

# Start MariaDB
echo "[2/4] Starting MariaDB..."
mysqld_safe --skip-grant-tables &
sleep 5

# Wait for MariaDB to be ready
for i in {1..15}; do
    if mysqladmin ping --silent 2>/dev/null; then
        break
    fi
    sleep 1
done

# Setup database
echo "[3/4] Setting up database..."
if [ -f "/docker-entrypoint-initdb.d/setup.sql" ]; then
    mysql -u root < /docker-entrypoint-initdb.d/setup.sql
    echo "✓ Database created successfully"
fi

# Fix MySQL auth for PHP
mysql -u root -e "UPDATE mysql.user SET plugin='mysql_native_password' WHERE user='root'; FLUSH PRIVILEGES;" 2>/dev/null || true

# Start Apache
echo "[4/4] Starting Apache..."
echo ""
echo "========================================"
echo "  SecureBank is Ready!"
echo "========================================"
echo ""
echo "  Access: http://localhost:8080/bankapp/"
echo ""

exec apachectl -D FOREGROUND
