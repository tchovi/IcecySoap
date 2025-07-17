<?php include '../backend/config/db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Upload Music</title><link rel="stylesheet" href="assets/css/bootstrap.min.css"></head>
<body>
<div class="container mt-5">
  <h2>Upload MP3</h2>
  <form action="../backend/api/music.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" accept="audio/mpeg" class="form-control mb-3" required>
    <input type="text" name="tags" placeholder="Tags" class="form-control mb-3">
    <button type="submit" class="btn btn-primary">Upload</button>
  </form>
</div>
</body>
</html>
