<?php
include dirname(__DIR__)."\php\apis\itunes\Itunes.php";
include dirname(__DIR__)."\php\apis\soundcloud\Soundcloud.php";
include dirname(__DIR__)."\php\apis\youtube\Youtube.php";

$params = parse_ini_file("api.conf");
foreach($params as $key=>$value){
	define($key, $value);
}
