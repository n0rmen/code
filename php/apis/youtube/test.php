<?php
include "youtube.api.php";

$yt = new Youtube(array('api_key' => "YOUR_API_KEY"));

$results = $yt->getVideo("GN-_rbIV0jc");
// $results = $yt->searchVideos("Son du coin");
// $results = $yt->getPlaylist("PLxKMn3Pf11PiiVxjXv0Lvb6-XQiABbZZY");
// $results = $yt->getPlaylistItems("PLxKMn3Pf11PiiVxjXv0Lvb6-XQiABbZZY");
// $results = $yt->searchPlaylists("Son du coin");
// $results = $yt->getChannel("UCpAyqgNZY--jEGmidNSDr8w");
// $results = $yt->searchChannels("Son du coin");
// $results = $yt->getVideoIdFromUrl("https://youtu.be/GN-_rbIV0jc");
// $results = $yt->getVideoIdFromUrl("https://www.youtube.com/embed/GN-_rbIV0jc");
// $results = $yt->getVideoIdFromUrl("https://www.youtube.com/watch?v=GN-_rbIV0jc");
var_dump($results);
