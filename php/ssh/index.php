<?php
$ssh_host = "";
$ssh_port = 22;
$ssh_username = "";
$ssh_password = "";

$ssh = ssh2_connect($ssh_host, $ssh_port);
$auth = ssh2_auth_password($ssh, $ssh_username, $ssh_password);
$content = ssh2_exec($ssh, "pwd");
var_dump($content);
