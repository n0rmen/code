<?php
include "php/apis/soundcloud/Soundcloud.php";
$params = parse_ini_file("api.conf");
define("SOUNDCLOUD_CLIENT_ID", $params['SOUNDCLOUD_CLIENT_ID']);

class SoundcloudTest extends PHPUnit_Framework_TestCase{
	
	private $api;
	
	public function __construct(){
		$this->api = new Soundcloud(array('client_id' => SOUNDCLOUD_CLIENT_ID));
	}
	
	public function test_resolve_Track(){
		$results = $this->api->resolve("https://soundcloud.com/blue-box/afterglow");
		
		$this->assertEquals(141186988, $results->id);
		$this->assertEquals("track", $results->kind);
	}
	
	public function test_resolve_Playlist(){
		$results = $this->api->resolve("https://soundcloud.com/blue-box/sets/afterglow-ep");
		
		$this->assertEquals(27891641, $results->id);
		$this->assertEquals("playlist", $results->kind);
	}
	
	public function test_resolve_User(){
		$results = $this->api->resolve("https://soundcloud.com/blue-box");
		
		$this->assertEquals(4267019, $results->id);
		$this->assertEquals("user", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_resolve_InvalidUrl(){
		$results = $this->api->resolve("http://www.google.fr");
	}
	
	public function test_getTrack(){
		$results = $this->api->getTrack(141186988);
		
		$this->assertEquals(141186988, $results->id);
		$this->assertEquals("track", $results->kind);
	}
	
	public function test_getTrack_FromUrl(){
		$results = $this->api->getTrack("https://soundcloud.com/blue-box/afterglow");
		
		$this->assertEquals(141186988, $results->id);
		$this->assertEquals("track", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getTrack_InvalidId(){
		$results = $this->api->getTrack(909253);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getTrack_InvalidUrl(){
		$results = $this->api->getTrack("http://www.google.fr");
	}
	
	public function test_searchTracks(){
		$results = $this->api->searchTracks("Afterglow");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/Afterglow/i", $results[0]->title);
		$this->assertEquals("track", $results[0]->kind);
	}
	
	public function test_getPlaylist(){
		$results = $this->api->getPlaylist(27891641);
		
		$this->assertEquals(27891641, $results->id);
		$this->assertEquals("playlist", $results->kind);
	}
	
	public function test_getPlaylist_FromUrl(){
		$results = $this->api->getPlaylist("https://soundcloud.com/blue-box/sets/afterglow-ep");
		
		$this->assertEquals(27891641, $results->id);
		$this->assertEquals("playlist", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getPlaylist_InvalidId(){
		$results = $this->api->getTrack(909253);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getPlaylist_InvalidUrl(){
		$results = $this->api->getPlaylist("http://www.google.fr");
	}
	
	public function test_getPlaylistTracks(){
		$results = $this->api->getPlaylistTracks(27891641);
		
		$this->assertTrue(is_array($results));
		$this->assertEquals("track", $results[0]->kind);
	}
	
	public function test_searchPlaylists(){
		$results = $this->api->searchPlaylists("Afterglow - EP");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/Afterglow/i", $results[0]->title);
		$this->assertEquals("playlist", $results[0]->kind);
	}
	
	public function test_getUser(){
		$results = $this->api->getUser(4267019);
		
		$this->assertEquals(4267019, $results->id);
		$this->assertEquals("user", $results->kind);
	}
	
	public function test_getUser_FromUrl(){
		$results = $this->api->getUser("https://soundcloud.com/blue-box");
		
		$this->assertEquals(4267019, $results->id);
		$this->assertEquals("user", $results->kind);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getUser_InvalidId(){
		$results = $this->api->getUser(879273552);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getUser_InvalidUrl(){
		$results = $this->api->getUser("http://www.google.fr");
	}
	
	public function test_getUserPlaylists(){
		$results = $this->api->getUserPlaylists(4267019);
		
		$this->assertTrue(is_array($results));
		$this->assertEquals("playlist", $results[0]->kind);
		$this->assertEquals(4267019, $results[0]->user_id);
	}
	
	public function test_getUserTracks(){
		$results = $this->api->getUserTracks(4267019);
		
		$this->assertTrue(is_array($results));
		$this->assertEquals("track", $results[0]->kind);
		$this->assertEquals(4267019, $results[0]->user_id);
	}
	
	public function test_searchUsers(){
		$results = $this->api->searchUsers("Blue box");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/Blue box/i", $results[0]->username);
		$this->assertEquals("user", $results[0]->kind);
	}
}
