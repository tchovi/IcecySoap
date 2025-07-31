# IcecySoap
An ultra-lightweight web-based radio automation and streaming control panel for internet radio broadcasters with essential tools to manage, schedule, and stream live and, or AutoDJ content.  The code for the back end and front end using Node.js, liquidsoap for AutoDj system, LiteSQL for database, Icecast2 for Streaming engine. Enjoy

A lightweight internet radio automation and streaming control panel using:

- Node.js for backend API
- Vue.js for frontend SPA
- LiteSQL (SQLite) for database
- Liquidsoap for AutoDJ playback
- Icecast2 for audio streaming
- NGINX for frontend serving

## Features

- User authentication with password change prompt on first login
- Music upload and playlist creation
- Time-based scheduling
- Live listener stats from Icecast
- Web audio player for embedding

## Setup Instructions

1. Clone the project:
```bash
git clone https://github.com/tchovi/IcecySoap.git
cd IcecySoap
```

2. Run setup:
```bash
chmod +x scripts/*.sh
sudo ./scripts/install.sh
```

3. Access panel:
- Visit `http://localhost`
- Login: `admin` / `changeme123`

4. Change admin password using CLI:
```bash
node scripts/change-password.js
```

## Notes

- Music files are stored in `/var/www/webradio/Music`
- AutoDJ and Icecast start automatically on install

MIT License
