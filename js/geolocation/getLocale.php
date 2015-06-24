<?php

function getLocalesFromFile(){
	$filepath = "locales.json";
	if(!is_file($filepath)) throw new Exception("No data");

	$content = file_get_contents($filepath);
	$locales = json_decode($content);
	if(sizeof($locales) == 0) throw new Exception("No locales found");
	
	return $locales;
}

function getLocalesFromDb(){
	// to implement
	throw new Exception("No data");
}

function getDistance($lat_a, $lat_b, $lon_a, $lon_b){
	$earth_radius = 6372795.477598;
	$alpha = ($lat_b - $lat_a) / 2;
	$beta = ($lon_b - $lon_a) / 2;
	$a = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat_a)) * cos(deg2rad($lat_b)) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
	$c = asin(min(1, sqrt($a)));
	$distance = 2 * $earth_radius * $c;
	
	return round($distance);
}

if(!empty($_GET['latitude']) && !empty($_GET['longitude'])){
	try{
		$locales = getLocalesFromFile();
		foreach($locales as $locale){
			if($locale->coords->latitude == $_GET['latitude'] && $locale->coords->longitude == $_GET['longitude']){
				$locale->coords->distance = 0;
				$result = $locale;
				break;
			}
			else $locale->coords->distance = getDistance($locale->coords->latitude, $_GET['latitude'], $locale->coords->longitude, $_GET['longitude']);
		}
		
		if(empty($result)){
			usort($locales, function($a, $b){
				return ($a->coords->distance < $b->coords->distance) ? -1 : 1;
			});
			$result = $locales[0];
		}
		
		die(json_encode($result));
	}
	catch(Exception $e){
		$response = array('error' => array('message' => $e->getMessage()));
		die(json_encode($response));
	}
}

header('HTTP/1.0 404 Not found');
die("Not found");
