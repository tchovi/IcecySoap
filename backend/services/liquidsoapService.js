const { exec } = require('child_process');

exports.startLiquidsoap = () => {
  return new Promise((resolve, reject) => {
    exec('liquidsoap ./liquidsoap/autodj.liq', (error, stdout, stderr) => {
      if (error) return reject(stderr);
      resolve(stdout);
    });
  });
};

exports.stopLiquidsoap = () => {
  return new Promise((resolve, reject) => {
    exec("pkill -f liquidsoap", (error, stdout, stderr) => {
      if (error) return reject(stderr);
      resolve(stdout);
    });
  });
};