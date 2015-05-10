<?php

$albums = array(
	array('title' => "Finger In The Noise - Finger in the noise EP", 'link' => "http://www.sonducoin.fr/album-592-finger-in-the-noise-ep", 'description' => "Finger In The Noise (rock) - Clamart (92) 1. Le vieux singe 2. Blow up 3. Wasted time 4. LM"),
	array('title' => "Full Throttle Baby - Full Throttle Baby II", 'link' => "http://www.sonducoin.fr/album-588-full-throttle-baby-ii", 'description' => "Full Throttle Baby (rock) - Cergy (95), Pontoise (95), Paris (75) 1. Blue balls 2. Don't touch my moped 3. Surfin' 4. Take your teeth back 5. Try again"),
	array('title' => "Jabul Gorba - Un diable au paradis", 'link' => "http://www.sonducoin.fr/album-586-un-diable-au-paradis", 'description' => "Jabul Gorba (ska,punk,tzigane) - Mantes la Jolie (78), Les Mureaux (78) 1. La neige 2. Flobecq 3. Les vilaines sorcières 4. Un diable au paradis")
);

$xml = new SimpleXMLElement('<xml/>');
$xml->addAttribute('version', "1.0");
$xml->addAttribute('encoding', "utf-8");
$rss = $xml->addChild('rss');
$rss->addAttribute('version', "2.0");
$channel = $rss->addChild('channel');
$channel->addChild('title', "Le Son du coin");
$channel->addChild('link', "http://www.sonducoin.fr/rss/albums");
$channel->addChild('description', "La plateforme de streaming 100% locale");
$channel->addChild('language', "fr-fr");

foreach($albums as $album){
    $item = $channel->addChild('item');
    $item->addChild('title', $album['title']);
    $item->addChild('link', $album['link']);
    $item->addChild('description', $album['description']);
}

Header('Content-type: text/xml');
die($xml->asXML());
