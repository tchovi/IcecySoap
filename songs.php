<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');
$conn = DB::getInstance();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': // Upload
        $target = MUSIC_DIR . '/' . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            // Extract ID3 tags using getID3 library
            $getID3 = new getID3;
            $info = $getID3->analyze($target);
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO songs (...) VALUES (...)");
            $stmt->execute();
            echo json_encode(['status' => 'success']);
        }
        break;
    case 'GET': // List songs
        $result = $conn->query("SELECT * FROM songs");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;
}
