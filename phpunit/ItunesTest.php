<?php
include "php/apis/itunes/Itunes.php";

class ItunesTest extends PHPUnit_Framework_TestCase{
	
	private $api;
	
	public function __construct(){
		$this->api = new Itunes();
	}
	
	public function test_getTrack(){
		$results = $this->api->getTrack(879273565);
		
		$this->assertEquals(879273565, $results->trackId);
		$this->assertEquals("track", $results->wrapperType);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getTrack_InvalidId(){
		$results = $this->api->getTrack(909253);
	}
	
	public function test_searchTracks(){
		$results = $this->api->searchTracks("Better Together");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/Better Together/i", $results[0]->trackName);
		$this->assertEquals("track", $results[0]->wrapperType);
	}
	
	public function test_getAlbum(){
		$results = $this->api->getAlbum(879273552);
		
		$this->assertEquals(879273552, $results->collectionId);
		$this->assertEquals("collection", $results->wrapperType);
	}
	
	public function test_getAlbum_FromUrl(){
		$results = $this->api->getAlbum("https://itunes.apple.com/us/album/in-between-dreams/id879273552");
		
		$this->assertEquals(879273552, $results->collectionId);
		$this->assertEquals("collection", $results->wrapperType);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getAlbum_InvalidId(){
		$results = $this->api->getTrack(909253);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getAlbum_InvalidUrl(){
		$results = $this->api->getAlbum("https://itunes.apple.com/us/artist/jack-johnson/id909253");
		
		$this->assertNull($results);
	}
	
	public function test_getAlbumTracks(){
		$results = $this->api->getAlbumTracks(879273552);
		
		$this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("track", $result->wrapperType);
			$this->assertEquals(879273552, $result->collectionId);
		}
	}
	
	public function test_searchAlbums(){
		$results = $this->api->searchAlbums("In Between Dreams");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/In Between Dreams/i", $results[0]->collectionName);
		$this->assertEquals("collection", $results[0]->wrapperType);
	}
	
	public function test_getArtist(){
		$results = $this->api->getArtist(909253);
		
		$this->assertEquals(909253, $results->artistId);
		$this->assertEquals("artist", $results->wrapperType);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function test_getArtist_InvalidId(){
		$results = $this->api->getArtist(879273552);
	}
	
	public function test_getArtistAlbums(){
		$results = $this->api->getArtistAlbums(909253);
		
		$this->assertTrue(is_array($results));
		foreach($results as $result){
			$this->assertEquals("collection", $result->wrapperType);
			$this->assertEquals(909253, $result->artistId);
		}
	}
	
	public function test_searchArtists(){
		$results = $this->api->searchArtists("Jack Johnson");
		
		$this->assertTrue(is_array($results));
		$this->assertRegExp("/Jack Johnson/i", $results[0]->artistName);
		$this->assertEquals("artist", $results[0]->wrapperType);
	}
}
