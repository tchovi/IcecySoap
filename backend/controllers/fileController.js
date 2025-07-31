const path = require('path');
const fs = require('fs');
const multer = require('multer');
const { musicDir } = require('../config');

const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, musicDir),
  filename: (req, file, cb) => cb(null, file.originalname)
});

const upload = multer({ storage }).single('file');

exports.uploadFile = (req, res) => {
  upload(req, res, err => {
    if (err) return res.status(500).json({ error: 'Upload failed' });
    res.json({ message: 'File uploaded successfully', filename: req.file.originalname });
  });
};

exports.listFiles = (req, res) => {
  fs.readdir(musicDir, (err, files) => {
    if (err) return res.status(500).json({ error: 'Failed to list files' });
    res.json(files);
  });
};