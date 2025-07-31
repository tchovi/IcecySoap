-- Create users table
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT UNIQUE NOT NULL,
  password TEXT NOT NULL,
  mustChangePassword INTEGER DEFAULT 1
);

-- Create playlists table
CREATE TABLE IF NOT EXISTS playlists (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  schedule_time TEXT
);

-- Create tracks table
CREATE TABLE IF NOT EXISTS tracks (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  playlist_id INTEGER,
  filename TEXT,
  FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);
