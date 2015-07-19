<?php
include "php/apis/youtube/Youtube.php";
$params = parse_ini_file("api.conf");
define("GOOGLE_API_KEY", $params['GOOGLE_API_KEY']);

class YoutubeTest extends PHPUnit_Framework_TestCase{
	
	private $api;
	
	public function __construct(){
		$this->api = new Youtube(array('api_key' => GOOGLE_API_KEY));
	}
	
	public function test_resolve(){
		$results = $this->api->resolve("https://www.youtube.com/watch?v=GN-_rbIV0jc");
		
		$this->assertEquals("GN-_rbIV0jc", $results->id);
		$this->assertEquals("youtube#video", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_resolve_InvalidUrl(){
		$results = $this->api->resolve("http://www.google.fr");
	}
	
	public function test_getVideo(){
		$results = $this->api->getVideo("GN-_rbIV0jc");
		
		$this->assertEquals("GN-_rbIV0jc", $results->id);
		$this->assertEquals("youtube#video", $results->kind);
	}
	
	public function test_getVideo_FromUrl(){
		$results = $this->api->getVideo("https://www.youtube.com/watch?v=GN-_rbIV0jc");
		
		$this->assertEquals("GN-_rbIV0jc", $results->id);
		$this->assertEquals("youtube#video", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getVideo_InvalidId(){
		$results = $this->api->getVideo(909253);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getVideo_InvalidUrl(){
		$results = $this->api->getVideo("http://www.google.fr");
	}
	
	public function test_searchVideos(){
		$results = $this->api->searchVideos("son du coin");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/son du coin/i", $results[0]->snippet->title);
		$this->assertEquals("youtube#video", $results[0]->id->kind);
	}
	
	public function test_getPlaylist(){
		$results = $this->api->getPlaylist("PLxKMn3Pf11PiiVxjXv0Lvb6-XQiABbZZY");
		
		$this->assertEquals("PLxKMn3Pf11PiiVxjXv0Lvb6-XQiABbZZY", $results->id);
		$this->assertEquals("youtube#playlist", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getPlaylist_InvalidId(){
		$results = $this->api->getVideo(909253);
	}
	
	public function test_getPlaylistVideos(){
		$results = $this->api->getPlaylistVideos("PLxKMn3Pf11PiiVxjXv0Lvb6-XQiABbZZY");
		
		$this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("youtube#playlistItem", $result->kind);
		}
	}
	
	public function test_searchPlaylists(){
		$results = $this->api->searchPlaylists("son du coin");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/son du coin/i", $results[0]->snippet->title);
		$this->assertEquals("youtube#playlist", $results[0]->id->kind);
	}
	
	public function test_getChannel(){
		$results = $this->api->getChannel("UCpAyqgNZY--jEGmidNSDr8w");
		
		$this->assertEquals("UCpAyqgNZY--jEGmidNSDr8w", $results->id);
		$this->assertEquals("youtube#channel", $results->kind);
	}
	
	public function test_getChannel_FromUrl(){
		$results = $this->api->getChannel("https://www.youtube.com/channel/UCDgEglMcFTNpDbGEeQcummw");
		
		$this->assertEquals("UCDgEglMcFTNpDbGEeQcummw", $results->id);
		$this->assertEquals("youtube#channel", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getChannel_InvalidId(){
		$results = $this->api->getChannel(879273552);
	}
	
	public function test_getChannelPlaylists(){
		$results = $this->api->getChannelPlaylists("UC3G1JeFGUpMdS72A9Iq3DOQ");
		
		$this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("youtube#playlist", $result->kind);
			$this->assertEquals("UC3G1JeFGUpMdS72A9Iq3DOQ", $result->snippet->channelId);
		}
	}
	
	public function test_getChannelVideos(){
		$results = $this->api->getChannelVideos("UCpAyqgNZY--jEGmidNSDr8w");
		
		$this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("youtube#video", $result->id->kind);
			$this->assertEquals("UCpAyqgNZY--jEGmidNSDr8w", $result->snippet->channelId);
		}
	}
	
	public function test_searchChannels(){
		$results = $this->api->searchChannels("son du coin");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/son du coin/i", $results[0]->snippet->title);
		$this->assertEquals("youtube#channel", $results[0]->id->kind);
	}
}
