#!/bin/bash
set -e

# Update system
apt update && apt upgrade -y

# Install web + DB stack
apt install -y apache2 mariadb-server php php-mysql php-cli php-curl php-xml php-mbstring unzip curl ffmpeg

# Audio & stream dependencies
apt install -y icecast2 opam git m4 bubblewrap libffi-dev pkg-config \
  libmad0-dev libtag1-dev libvorbis-dev libopus-dev libsamplerate0-dev libssl-dev libmp3lame-dev libfaad-dev

# Initialize OPAM and install Liquidsoap under user context
su -l $SUDO_USER -c "opam init -y --bare --disable-sandboxing"
su -l $SUDO_USER -c "opam install -y liquidsoap"

# Create music directory
mkdir -p /var/lib/icecysoap/music
chown -R www-data:www-data /var/lib/icecysoap/music

# Configure MariaDB
systemctl enable mariadb
systemctl start mariadb
mysql -e "CREATE DATABASE IF NOT EXISTS icecysoap;"
mysql -e "CREATE USER IF NOT EXISTS 'radioadmin'@'localhost' IDENTIFIED BY 'securepassword';"
mysql -e "GRANT ALL PRIVILEGES ON icecysoap.* TO 'radioadmin'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
mysql icecysoap < ../sql/schema.sql

# Start services
systemctl enable apache2
systemctl enable icecast2
systemctl start apache2
systemctl start icecast2

clear
echo '✅ IcecySoap WSL installation complete. Visit http://localhost:8088'
