#!/bin/bash
set -e
apt update && apt upgrade -y
apt install -y apache2 mariadb-server php php-mysql php-cli php-curl php-xml php-mbstring unzip curl ffmpeg
apt install -y icecast2 opam git m4 bubblewrap libffi-dev pkg-config
su -c "opam init -y --disable-sandboxing && eval \$(opam env) && opam install -y liquidsoap" -s /bin/bash $SUDO_USER
mkdir -p /var/lib/icecysoap/music
chown -R www-data:www-data /var/lib/icecysoap/music
mysql -e "CREATE DATABASE IF NOT EXISTS icecysoap;"
mysql -e "CREATE USER IF NOT EXISTS 'radioadmin'@'localhost' IDENTIFIED BY 'securepassword';"
mysql -e "GRANT ALL PRIVILEGES ON icecysoap.* TO 'radioadmin'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
mysql icecysoap < ../sql/schema.sql
systemctl enable apache2
systemctl enable mariadb
systemctl enable icecast2
systemctl start apache2
systemctl start mariadb
systemctl start icecast2
clear
echo "✅ IcecySoap WSL installation completed. Access at http://localhost:8088"
