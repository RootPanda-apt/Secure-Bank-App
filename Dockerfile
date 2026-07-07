FROM kalilinux/kali-rolling:latest

LABEL description="SecureBank Lab - Vulnerable Banking App"

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y \
        apache2 \
        mariadb-server \
        php \
        php-mysql \
        libapache2-mod-php \
        curl \
        netcat-traditional \
        && apt-get clean && \
        rm -rf /var/lib/apt/lists/*

COPY bankapp /var/www/html/bankapp
COPY sql/setup.sql /docker-entrypoint-initdb.d/
COPY docker-entrypoint.sh /usr/local/bin/

RUN chown -R www-data:www-data /var/www/html/bankapp && \
    chmod -R 755 /var/www/html/bankapp && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

RUN a2enmod rewrite && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
