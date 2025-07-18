CREATE DATABASE icecysoap;
USE icecysoap;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','dj') DEFAULT 'dj',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE songs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(100),
    album VARCHAR(100),
    genre VARCHAR(50),
    duration INT NOT NULL,
    file_path VARCHAR(255) UNIQUE NOT NULL,
    play_count INT DEFAULT 0,
    last_played DATETIME
);

CREATE TABLE playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT
);

CREATE TABLE playlist_songs (
    playlist_id INT NOT NULL,
    song_id INT NOT NULL,
    position INT NOT NULL,
    PRIMARY KEY (playlist_id, song_id)
);

CREATE TABLE schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    playlist_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    is_recurring BOOLEAN DEFAULT false,
    recur_pattern ENUM('daily','weekly','monthly') DEFAULT 'daily'
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('connection','playback') NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
