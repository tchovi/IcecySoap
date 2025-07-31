const express = require('express');
const router = express.Router();
const fileController = require('../controllers/fileController');
const auth = require('../utils/authMiddleware');

router.post('/upload', auth, fileController.uploadFile);
router.get('/', auth, fileController.listFiles);

module.exports = router;