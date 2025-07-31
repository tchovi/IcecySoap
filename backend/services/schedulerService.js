const sqlite3 = require('sqlite3').verbose();
const { dbPath, musicDir } = require('../config');
const db = new sqlite3.Database(dbPath);
const fs = require('fs');
const path = require('path');

// This module can be expanded to generate .liq playlist for scheduled times
exports.generatePlaylistFile = (callback) => {
  db.all("SELECT filename FROM tracks", [], (err, rows) => {
    if (err) return callback(err);
    const playlistContent = rows.map(r => `audio.insert("${path.resolve(musicDir, r.filename)}")`).join('\n');
    fs.writeFile('./liquidsoap/playlist.liq', playlistContent, callback);
  });
};