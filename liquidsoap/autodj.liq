# Liquidsoap AutoDJ Script
set("log.file.path","/var/log/liquidsoap/liquidsoap.log")
set("server.telnet", true)
set("server.telnet.port", 1234)

music = playlist(mode="random", "/var/www/webradio/Music")
output.icecast(%mp3,
  host = "localhost", port = 8000,
  password = "hackme",
  mount = "stream",
  name = "Web Radio",
  description = "Ultra-light Web Radio",
  genre="Various",
  url="http://localhost:8000",
  music)