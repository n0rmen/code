<?php
// https://github.com/alaouy/Youtube/blob/master/src/Alaouy/Youtube/Youtube.php

class Youtube{
	protected $api_key;
	
	function __construct($params){
		if(empty($params['api_key'])) throw Exception("Missing API key");
		
		$this->api_key = $params['api_key'];
	}
	
	public function getVideo($id){
		$params = array(
			'id' => $id,
			'part' => 'id, snippet, contentDetails', //id, snippet, contentDetails, player, statistics, status'
		);
		
		$data = $this->get("https://www.googleapis.com/youtube/v3/videos", $params);
		
		return json_decode($data);
	}
	
	public function searchVideos($q, $options=array()){
		$params = array(
			'q' => $q,
			'part' => 'id, snippet'
		);
		if(isset($options['limit'])) $params['maxResults'] = $options['limit'];
		
		$data = $this->get("https://www.googleapis.com/youtube/v3/search", $params);
		
		return json_decode($data);
	}
	
	public function searchVideosInChannel($q, $channelId, $options=array()){
		$params = array(
			'q' => $q,
			'type' => 'video',
			'part' => 'id, snippet'
		);
		
		$params = array(
			'q' => $q,
			'type' => 'video',
			'part' => 'id, snippet',
			'channelId' => $channelId
		);
		if(isset($options['limit'])) $params['maxResults'] = $options['limit'];
		
		$data = $this->get("https://www.googleapis.com/youtube/v3/search", $params);
		
		return json_decode($data);
	}
	
	public function get($url, $params) {
		$params['key'] = $this->api_key;
		
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url.(strpos($url, '?') === false ? '?' : '').http_build_query($params));
		if(strpos($url, 'https') === false) curl_setopt($c, CURLOPT_PORT, 80);
		else curl_setopt($c, CURLOPT_PORT, 443);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		
		$data = curl_exec($c);
		
		if(curl_errno($c)) throw new \Exception("Curl error : ".curl_error($c));
		
		return $data;
	}
}



/* TESTS */
$yt = new Youtube(array('api_key' => "AIzaSyDYl_E8dJgEeIF3L3kDLBUfOxKL6qvt8Sk"));

$video = $yt->getVideo("GN-_rbIV0jc");
