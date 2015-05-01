<?php
function login($username, $password){
	return $username == "admin" && $password == "admin";
}

if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])){
	var_dump(getallheaders());
	var_dump($_POST);
}
else{
	header('WWW-Authenticate: Basic realm="Restricted access"');
    header('HTTP/1.0 401 Unauthorized');
    die("Restricted access");
}
