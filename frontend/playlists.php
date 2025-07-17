<?php include '../backend/config/db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Playlists</title><link rel="stylesheet" href="assets/css/bootstrap.min.css"></head>
<body>
<div class="container mt-5">
  <h2>Playlists</h2>
  <form action="../backend/api/playlist.php" method="POST">
    <input type="text" name="name" placeholder="Playlist Name" class="form-control mb-3" required>
    <button type="submit" class="btn btn-success">Create Playlist</button>
  </form>
</div>
</body>
</html>
