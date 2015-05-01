<?php
$host = "";
$port = 21;
$username = "";
$password = "";

$ftp = ftp_connect($host, $port);
ftp_login($ftp, $username, $password);

ftp_mkdir($ftp, "doc/tmp");
ftp_chdir($ftp, "doc/tmp");
echo "<p>current directory: ".ftp_pwd($ftp)."</p>";

ftp_put($ftp, "pouet.txt", "pouet.txt", FTP_BINARY);
ftp_get($ftp, "pouet2.txt", "pouet.txt", FTP_BINARY);
echo "<p>pouet2.txt: ".file_get_contents("pouet2.txt")."</p>";
unlink("pouet2.txt");

ftp_delete($ftp, "pouet.txt");
ftp_chdir($ftp, "../");
ftp_rmdir($ftp, "tmp");

ftp_close($ftp);
