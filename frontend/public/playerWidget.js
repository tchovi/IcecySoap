(function() {
  const audio = document.createElement('audio');
  audio.src = "http://localhost:8000/stream";
  audio.controls = true;
  document.body.appendChild(audio);
})();