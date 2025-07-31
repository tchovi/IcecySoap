const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const userModel = require('../models/userModel');
require('dotenv').config();

exports.login = (req, res) => {
  const { username, password } = req.body;
  userModel.findUser(username, (err, user) => {
    if (err || !user) return res.status(401).json({ error: 'Invalid username or password' });

    bcrypt.compare(password, user.password, (err, match) => {
      if (!match) return res.status(401).json({ error: 'Invalid credentials' });

      const token = jwt.sign({ username: user.username }, process.env.JWT_SECRET, { expiresIn: '2h' });
      res.json({ token, mustChangePassword: !!user.mustChangePassword });
    });
  });
};

exports.changePassword = (req, res) => {
  const { username } = req.user;
  const { newPassword } = req.body;
  userModel.updatePassword(username, newPassword, (err) => {
    if (err) return res.status(500).json({ error: 'Failed to change password' });
    res.json({ message: 'Password updated successfully' });
  });
};