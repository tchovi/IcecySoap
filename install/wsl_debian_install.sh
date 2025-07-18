/* install/wsl_debian_install.sh */
#!/bin/bash

set -e

# Update system
apt update && apt upgrade -y

# Install Apache, PHP, MariaDB
apt install -y apache2 mariadb-server php php-mysql php-cli php-curl php-xml php-mbstring unzip curl ffmpeg

# Install Icecast2, OPAM, and dependencies
apt install -y icecast2 opam git m4 bubblewrap libffi-dev pkg-config

# Install Liquidsoap with all plugins
apt install -y lame libmad0 libtag1-dev
su -c "opam init -y --disable-sandboxing && eval \$(opam env) && opam install -y liquidsoap liquidsoap-plugin-cry liquidsoap-plugin-taglib liquidsoap-plugin-ffmpeg" -s /bin/bash $SUDO_USER

# Create media directory
mkdir -p /var/lib/icecysoap/music
chown -R www-data:www-data /var/lib/icecysoap/music

# Ensure MariaDB is started before SQL import
systemctl enable mariadb
systemctl start mariadb

# Configure MariaDB and import schema
mysql -e "CREATE DATABASE IF NOT EXISTS icecysoap;"
mysql -e "CREATE USER IF NOT EXISTS 'radioadmin'@'localhost' IDENTIFIED BY 'securepassword';"
mysql -e "GRANT ALL PRIVILEGES ON icecysoap.* TO 'radioadmin'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
mysql icecysoap < ../sql/schema.sql

# Enable and start services
systemctl enable apache2
systemctl enable icecast2
systemctl start apache2
systemctl start icecast2

clear
echo "✅ IcecySoap WSL installation completed. Access the web UI at http://localhost:8088"
