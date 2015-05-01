<?php

function curl($url, $method="GET", $params=array(), $header=array("Content-Type: application/x-www-form-urlencoded")){
	$c = curl_init();
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($c, CURLOPT_HTTPHEADER, $header);
	if(isset($params['username']) && isset($params['password'])){
		curl_setopt($c, CURLOPT_USERPWD, $params['username'].":".$params['password']);
		unset($params['username']);
		unset($params['password']);
	}
	if($method == "POST"){
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params, null, "&"));
	}
	$content = curl_exec($c);
	//$status  = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close($c);
	return $content;
}

$url = "http://localhost/github/php/curl/server/public.php";
echo $content = curl($url, "GET");

$url = "http://localhost/github/php/curl/server/private.php";
echo curl($url, "POST", array('username' => "admin", 'password' => "admin", 'type' => "POST request"), array());
echo curl($url, "POST", array('type' => "POST request"), array('Authorization: Basic '.base64_encode("admin:admin")));
