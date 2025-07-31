const express = require('express');
const router = express.Router();
const playlistController = require('../controllers/playlistController');
const auth = require('../utils/authMiddleware');

router.post('/create', auth, playlistController.createPlaylist);
router.get('/', auth, playlistController.getPlaylists);
router.post('/add-track', auth, playlistController.addTrack);
router.get('/:playlist_id/tracks', auth, playlistController.getTracks);

module.exports = router;