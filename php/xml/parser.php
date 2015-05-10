<?php

$url = "albums.rss";
$content = file_get_contents($url);
if(!empty($content)){
	$xml = new SimpleXMLElement($content);
	
	echo "<!DOCTYPE html>";
	echo "<html>";
	echo "<head>";
	echo "<title>".$xml->channel->title."</title>";
	echo "<meta charset=\"utf-8\">";
	echo "</head>";
	echo "<body>";
	echo "<h1>".$xml->channel->title."</h1>";
	echo "<p>".$xml->channel->link."</p>";
	echo "<p>".$xml->channel->description."</p>";
	echo "<p>".$xml->channel->language."</p>";
	echo "<ul>";
	foreach($news = $xml->channel->item as $item){
		echo "<li>";
		echo "<h2>".$item->title."</h2>";
		echo "<p>".$item->link."</p>";
		echo "<p>".$item->description."</p>";
		echo "</li>";
	}
	echo "</ul>";
	echo "</body>";
	echo "</html>";
}