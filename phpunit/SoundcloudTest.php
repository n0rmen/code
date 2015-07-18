<?php

class SoundcloudTest extends PHPUnit_Framework_TestCase{
	
	private $api;
	
	public function __construct(){
		$this->api = new Soundcloud(array('client_id' => SOUNDCLOUD_CLIENT_ID));
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
	
	public function test_getTrack_InvalidId(){
        $results = $this->api->getTrack(909253);
		
        $this->assertNull($results);
    }
	
    public function test_getTrack_InvalidUrl(){
        $results = $this->api->getTrack("https://itunes.apple.com/us/album/in-between-dreams/id879273552");
		
        $this->assertNull($results);
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
	
	public function test_getPlaylist_InvalidId(){
        $results = $this->api->getTrack(909253);

        $this->assertNull($results);
    }
	
    public function test_getPlaylist_InvalidUrl(){
        $results = $this->api->getPlaylist("https://itunes.apple.com/us/artist/jack-johnson/id909253");
		
        $this->assertNull($results);
    }
	
    public function test_getPlaylistTracks(){
        $results = $this->api->getPlaylistTracks(27891641);
		
        $this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("track", $result->kind);
		}
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
	
	public function test_getUser_InvalidId(){
        $results = $this->api->getUser(879273552);

        $this->assertNull($results);
    }
	
    public function test_getUserPlaylists(){
        $results = $this->api->getUserPlaylists(4267019);
		
        $this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("playlist", $result->kind);
			$this->assertEquals(4267019, $result->user_id);
		}
    }
	
    public function test_getUserTracks(){
        $results = $this->api->getUserTracks(4267019);
		
        $this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("track", $result->kind);
			$this->assertEquals(4267019, $result->user_id);
		}
    }
	
	public function test_searchUsers(){
        $results = $this->api->searchUsers("Blue box");
		
        $this->assertTrue(is_array($results));
        $this->assertRegExp("/Blue box/i", $results[0]->username);
        $this->assertEquals("user", $results[0]->kind);
    }
}
