#!/bin/bash
set -e

echo "🔧 Installing dependencies..."
sudo apt update
sudo apt install -y nodejs npm sqlite3 icecast2 liquidsoap nginx

echo "🧱 Setting up project directories..."
mkdir -p /var/www/webradio/Music
cp -r ./backend/models/db.litesql /var/www/webradio/backend/models/
sqlite3 /var/www/webradio/backend/models/db.litesql < ./backend/initDB.sql

echo "📦 Installing Node backend dependencies..."
cd backend
npm install
cd ..

echo "🔁 Starting services..."
pkill -f icecast || true
pkill -f liquidsoap || true
icecast -c ./icecast/icecast.xml &
liquidsoap ./liquidsoap/autodj.liq &

echo "✅ Installation complete."