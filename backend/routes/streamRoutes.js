const express = require('express');
const router = express.Router();
const streamController = require('../controllers/streamController');
const auth = require('../utils/authMiddleware');

router.post('/start', auth, streamController.startStream);
router.post('/stop', auth, streamController.stopStream);

module.exports = router;