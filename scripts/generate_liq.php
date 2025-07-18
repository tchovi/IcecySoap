<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../api/db.php';

$conn = DB::getInstance();
$output = "# Auto-generated Liquidsoap config\n\n";

// Get playlists
$result = $conn->query("SELECT * FROM playlists");
while($row = $result->fetch_assoc()) {
    $output .= "{$row['name']} = playlist(reload=300, \"{$row['name']}.m3u\")\n";
}

$output .= <<<EOT

radio = random(weights=[1,1,1], [HeavyRotation, LightRotation, GeneralRotation])

# Output to Icecast
output.icecast(
    %mp3(bitrate=128),
    host="localhost",
    port=8000,
    password="hackme",
    mount="/live.mp3",
    radio
)
EOT;

file_put_contents('/etc/liquidsoap/radio.liq', $output);
