<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../inc/functions.php';

header('Content-Type: application/json');
$conn = DB::getInstance();

// Authentication middleware
if (!validateApiKey()) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all songs or specific song by ID
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM songs WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $query = "SELECT * FROM songs";
            
            // Add filters if provided
            $where = [];
            $params = [];
            $types = '';
            
            if (isset($_GET['artist'])) {
                $where[] = "artist LIKE ?";
                $params[] = '%' . $_GET['artist'] . '%';
                $types .= 's';
            }
            
            if (isset($_GET['title'])) {
                $where[] = "title LIKE ?";
                $params[] = '%' . $_GET['title'] . '%';
                $types .= 's';
            }
            
            if (!empty($where)) {
                $query .= " WHERE " . implode(" AND ", $where);
            }
            
            // Add pagination
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 50;
            $offset = ($page - 1) * $limit;
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
            
            $stmt = $conn->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            
            $songs = [];
            while ($row = $result->fetch_assoc()) {
                $songs[] = $row;
            }
            
            echo json_encode([
                'data' => $songs,
                'pagination' => [
                    'page' => (int)$page,
                    'limit' => (int)$limit,
                    'total' => getTotalSongs($conn)
                ]
            ]);
        }
        break;

    case 'POST':
        // Handle file upload
        if (!isset($_FILES['file']) {
            http_response_code(400);
            die(json_encode(['error' => 'No file uploaded']));
        }

        $file = $_FILES['file'];
        
        // Validate file
        $allowedTypes = ['audio/mpeg', 'audio/mp3'];
        if (!in_array($file['type'], $allowedTypes)) {
            http_response_code(415);
            die(json_encode(['error' => 'Only MP3 files are allowed']));
        }

        if ($file['size'] > 10485760) { // 10MB limit
            http_response_code(413);
            die(json_encode(['error' => 'File too large (max 10MB)']));
        }

        // Generate safe filename
        $filename = preg_replace('/[^a-zA-Z0-9\-\._]/', '', basename($file['name']));
        $targetPath = MUSIC_DIR . '/' . $filename;

        // Move file
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            http_response_code(500);
            die(json_encode(['error' => 'Failed to save file']));
        }

        // Extract metadata
        try {
            $getID3 = new getID3;
            $info = $getID3->analyze($targetPath);
            
            $title = $info['tags']['id3v2']['title'][0] ?? pathinfo($filename, PATHINFO_FILENAME);
            $artist = $info['tags']['id3v2']['artist'][0] ?? 'Unknown Artist';
            $album = $info['tags']['id3v2']['album'][0] ?? '';
            $genre = $info['tags']['id3v2']['genre'][0] ?? '';
            $duration = (int)($info['playtime_seconds'] ?? 0);
            
            // Insert into database
            $stmt = $conn->prepare("
                INSERT INTO songs 
                (title, artist, album, genre, duration, file_path) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "ssssis", 
                $title, 
                $artist, 
                $album, 
                $genre, 
                $duration, 
                $targetPath
            );
            
            if (!$stmt->execute()) {
                unlink($targetPath); // Clean up if DB insert fails
                throw new Exception("Database error: " . $stmt->error);
            }
            
            // Log the upload
            logEvent($conn, "Uploaded song: $title by $artist");
            
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id' => $stmt->insert_id,
                    'title' => $title,
                    'artist' => $artist
                ]
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        // Update song metadata
        parse_str(file_get_contents("php://input"), $_PUT);
        
        if (!isset($_PUT['id'])) {
            http_response_code(400);
            die(json_encode(['error' => 'Song ID required']));
        }
        
        $allowedFields = ['title', 'artist', 'album', 'genre'];
        $updates = [];
        $params = [];
        $types = '';
        
        foreach ($_PUT as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "$key = ?";
                $params[] = $value;
                $types .= 's';
            }
        }
        
        if (empty($updates)) {
            http_response_code(400);
            die(json_encode(['error' => 'No valid fields to update']));
        }
        
        $params[] = $_PUT['id'];
        $types .= 'i';
        
        $query = "UPDATE songs SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }
        break;

    case 'DELETE':
        // Delete song
        if (!isset($_GET['id'])) {
            http_response_code(400);
            die(json_encode(['error' => 'Song ID required']));
        }
        
        // First get file path
        $stmt = $conn->prepare("SELECT file_path FROM songs WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $song = $result->fetch_assoc();
        
        if (!$song) {
            http_response_code(404);
            die(json_encode(['error' => 'Song not found']));
        }
        
        // Delete from database
        $stmt = $conn->prepare("DELETE FROM songs WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        
        if ($stmt->execute()) {
            // Delete file
            if (file_exists($song['file_path'])) {
                unlink($song['file_path']);
            }
            
            logEvent($conn, "Deleted song ID: " . $_GET['id']);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $stmt->error]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Helper functions
function getTotalSongs($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM songs");
    return $result->fetch_assoc()['total'];
}

function logEvent($conn, $message) {
    $stmt = $conn->prepare("INSERT INTO logs (type, message) VALUES ('playback', ?)");
    $stmt->bind_param("s", $message);
    $stmt->execute();
}

function validateApiKey() {
    $headers = getallheaders();
    $apiKey = $headers['X-API-KEY'] ?? '';
    
    // In a real implementation, validate against database
    return $apiKey === 'your-secure-api-key';
}
