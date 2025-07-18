#!/bin/bash
set -e

# System updates
apt update && apt upgrade -y

# Install dependencies
apt install -y \
    apache2 mariadb-server php php-mysql php-curl \
    php-gd php-mbstring php-xml php-zip \
    icecast2 liquidsoap lame \
    getid3 taglib-dev

# Create project structure
mkdir -p /var/www/icecysoap/{api,admin,dj,public,inc,scripts,uploads}
mkdir -p /var/lib/icecysoap/music
chown -R www-data:www-data /var/lib/icecysoap

# Configure MySQL
mysql -e "CREATE DATABASE icecysoap;"
mysql -e "CREATE USER 'icecy_user'@'localhost' IDENTIFIED BY 'secure_password';"
mysql -e "GRANT ALL PRIVILEGES ON icecysoap.* TO 'icecy_user'@'localhost';"

# Apache configuration
cat > /etc/apache2/sites-available/icecysoap.conf <<EOF
<VirtualHost *:8088>
    DocumentRoot /var/www/icecysoap
    <Directory /var/www/icecysoap>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

a2ensite icecysoap
systemctl restart apache2

# Icecast configuration
sed -i 's/<port>8000<\/port>/<port>8000<\/port>\n    <bind-address>127.0.0.1<\/bind-address>/' /etc/icecast2/icecast.xml
systemctl restart icecast2

# Create systemd service for Liquidsoap
cat > /etc/systemd/system/liquidsoap.service <<EOF
[Unit]
Description=Liquidsoap Radio Stream
After=network.target

[Service]
User=www-data
ExecStart=/usr/bin/liquidsoap /etc/liquidsoap/radio.liq
Restart=always

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable liquidsoap
