const readline = require('readline');
const userModel = require('../backend/models/userModel');

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

rl.question("Enter username: ", (username) => {
  rl.question("Enter new password: ", (password) => {
    userModel.updatePassword(username, password, (err) => {
      if (err) console.error("Error changing password:", err);
      else console.log("Password changed successfully.");
      rl.close();
    });
  });
});