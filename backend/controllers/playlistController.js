const sqlite3 = require('sqlite3').verbose();
const { dbPath } = require('../config');
const db = new sqlite3.Database(dbPath);

exports.createPlaylist = (req, res) => {
  const { name, schedule_time } = req.body;
  db.run("INSERT INTO playlists (name, schedule_time) VALUES (?, ?)", [name, schedule_time], function(err) {
    if (err) return res.status(500).json({ error: 'Error creating playlist' });
    res.json({ id: this.lastID });
  });
};

exports.getPlaylists = (req, res) => {
  db.all("SELECT * FROM playlists", [], (err, rows) => {
    if (err) return res.status(500).json({ error: 'Error fetching playlists' });
    res.json(rows);
  });
};

exports.addTrack = (req, res) => {
  const { playlist_id, filename } = req.body;
  db.run("INSERT INTO tracks (playlist_id, filename) VALUES (?, ?)", [playlist_id, filename], function(err) {
    if (err) return res.status(500).json({ error: 'Error adding track' });
    res.json({ message: 'Track added' });
  });
};

exports.getTracks = (req, res) => {
  const { playlist_id } = req.params;
  db.all("SELECT * FROM tracks WHERE playlist_id = ?", [playlist_id], (err, rows) => {
    if (err) return res.status(500).json({ error: 'Error fetching tracks' });
    res.json(rows);
  });
};