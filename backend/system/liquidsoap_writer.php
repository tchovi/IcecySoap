<?php
$configPath = '/etc/icecysoap/liquidsoap.liq';
$musicPath = '/var/lib/icecysoap/music';
$log = "/var/log/icecysoap.liq";

$script = "
default = playlist(mode='random', reload=1, \"${musicPath}/*.mp3\")
output.icecast(%mp3(bitrate=128), host='localhost', port=8000, password='hackme', mount='live', name='IcecySoap Radio', genre='Various', description='Community Radio', url='http://localhost:8000', default)
";

file_put_contents($configPath, $script);
echo "Liquidsoap config written to $configPath\n";
?>
