const { exec } = require('child_process');

exports.startIcecast = () => {
  return new Promise((resolve, reject) => {
    exec('icecast -c ./icecast/icecast.xml', (error, stdout, stderr) => {
      if (error) return reject(stderr);
      resolve(stdout);
    });
  });
};

exports.stopIcecast = () => {
  return new Promise((resolve, reject) => {
    exec("pkill -f icecast", (error, stdout, stderr) => {
      if (error) return reject(stderr);
      resolve(stdout);
    });
  });
};