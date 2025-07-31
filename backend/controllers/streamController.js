const { startIcecast, stopIcecast } = require('../services/icecastService');
const { startLiquidsoap, stopLiquidsoap } = require('../services/liquidsoapService');

exports.startStream = async (req, res) => {
  try {
    await startIcecast();
    await startLiquidsoap();
    res.json({ message: 'Streaming started.' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to start stream.' });
  }
};

exports.stopStream = async (req, res) => {
  try {
    await stopLiquidsoap();
    await stopIcecast();
    res.json({ message: 'Streaming stopped.' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to stop stream.' });
  }
};