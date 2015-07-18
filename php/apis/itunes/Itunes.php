<?php
/**
 * Itunes API
 * 
 * @author Cyril Vermande (cyril [at] cyrilwebdesign.com)
 * @copyright 2015 Cyril Vermande
 * @license http://opensource.org/licenses/mit MIT
 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html
 */

/**
 * Class Itunes
 *
 * Example : $it = new Itunes();
 */
class Itunes{
	
	/**
	 * Get an iTunes track
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getTrack($uri, $options=array()){
		$result = $this->get("https://itunes.apple.com/lookup", array('id' => $uri));
		
		if($result->resultCount === 0 || $result->results[0]->wrapperType !== "track") throw new Exception("Resource not found");
		return $result->results[0];
	}
	
	/**
	 * Search iTunes tracks
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchTracks($q, $options=array()){
		$params = array('term' => urlencode($q), 'entity' => "song");
		
		$result = $this->get("https://itunes.apple.com/search", $params);
		
		return $result->results;
	}
	
	/**
	 * Get an iTunes album
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getAlbum($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getAlbumIdFromUrl($uri);
		
		$result = $this->get("https://itunes.apple.com/lookup", array('id' => $uri));
		
		if($result->resultCount === 0 || $result->results[0]->wrapperType !== "collection") throw new Exception("Resource not found");
		return $result->results[0];
	}
	
	/**
	 * Get the tracks of an iTunes album
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 * @throw Exception
	 */
	public function getAlbumTracks($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getAlbumIdFromUrl($uri);
		$params = array('id' => $uri, 'entity' => "song");
		
		$result = $this->get("https://itunes.apple.com/lookup", $params);
		
		array_shift($result->results);
		if($result->resultCount === 0 || $result->results[0]->wrapperType !== "track") throw new Exception("Resource not found");
		return $result->results;
	}
	
	/**
	 * Search iTunes albums
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchAlbums($q, $options=array()){
		$params = array('term' => urlencode($q), 'entity' => "album");
		
		$result = $this->get("https://itunes.apple.com/search", $params);
		
		return $result->results;
	}
	
	/**
	 * Get an iTunes album ID from a URL
	 * 
	 * @param string $url The resource URL
	 * 
	 * @return string|null
	 */
	public function getAlbumIdFromUrl($url){
		if(!filter_var($url, FILTER_VALIDATE_URL)) return null;
		if(!preg_match("/itunes.apple.com\/[a-z]{2}\/album\//", $url)) return null;
		
		$path = parse_url($url, PHP_URL_PATH);
		$matches = array();
		if(preg_match("/\/[a-z]{2}\/album\/[a-z\-]*\/id([0-9]*)/", $path, $matches)){
			if(isset($matches[1])) return $matches[1];
		}
		return null;
	}
	
	/**
	 * Get a iTunes artist
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getArtist($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getArtistIdFromUrl($uri);
		
		$result = $this->get("https://itunes.apple.com/lookup", array('id' => $uri));
		
		if($result->resultCount === 0 || $result->results[0]->wrapperType !== "artist") throw new Exception("Resource not found");
		return $result->results[0];
	}
	
	/**
	 * Get the albums of an iTunes artist
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 * @throw Exception
	 */
	public function getArtistAlbums($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getArtistIdFromUrl($uri);
		$params = array('id' => $uri, 'entity' => "album");
		
		$result = $this->get("https://itunes.apple.com/lookup", $params);
		
		array_shift($result->results);
		if($result->resultCount === 0 || $result->results[0]->wrapperType !== "collection") throw new Exception("Resource not found");
		return $result->results;
	}
	
	/**
	 * Search iTunes artists
	 * @link https://www.apple.com/itunes/affiliates/resources/documentation/itunes-store-web-service-search-api.html#lookup
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchArtists($q, $options=array()){
		$params = array('term' => urlencode($q), 'entity' => "musicArtist");
		
		$result = $this->get("https://itunes.apple.com/search", $params);
		
		return $result->results;
	}
	
	/**
	 * GET an iTunes artist ID from a URL
	 * 
	 * @param string $url The resource URL
	 * 
	 * @return string|null
	 */
	private function getArtistIdFromUrl($url){
		if(!filter_var($url, FILTER_VALIDATE_URL)) return null;
		if(!preg_match("/itunes.apple.com\/[a-z]{2}\/artist\//", $url)) return null;
		
		$path = parse_url($url, PHP_URL_PATH);
		$matches = array();
		if(preg_match("/\/[a-z]{2}\/artist\/[a-z\-]*\/id([0-9]*)/", $path, $matches)){
			if(isset($matches[1])) return $matches[1];
		}
		return null;
	}
	
	/**
	 * GET request
	 * 
	 * @param string $url The resource URL
	 * @param array $params Array of parameters
	 * 
	 * @return object Decoded JSON response
	 * @throw Exception
	 */
	private function get($url, $params=array()){
		$url .= (strpos($url, "?") === false ? "?" : "&").http_build_query($params);
		
		$json = $this->_curl($url, "GET");
		$result = json_decode($json);
		
		return $result;
	}
	
	/**
	 * POST request
	 * 
	 * @param string $url The resource URL
	 * @param array $params Array of parameters
	 * 
	 * @return object Decoded JSON response
	 * @throw Exception
	 */
	private function post($url, $params=array()){
		$url .= (strpos($url, "?") === false ? "?" : "&")."client_id=".$this->api_key;
		
		$json =  $this->_curl($url, "POST", $params);
		$result = json_decode($json);
		
		return $result;
	}
	
	/**
	 * CURL request
	 * 
	 * @param string $url The request URL
	 * @param string $method The request method
	 * @param array $params The request parameters
	 * @param array $header The request header
	 * 
	 * @return string JSON response
	 * @throw Exception
	 */
	private function _curl($url, $method='GET', $params=array(), $header=array('Content-Type: application/x-www-form-urlencoded')){
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		if($method=='POST'){
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params, null,'&'));
		}
		if(!empty($header)){
			curl_setopt($c, CURLOPT_HTTPHEADER, $header);
		}
		
		$content = curl_exec($c);
		
		if(curl_errno($c)) throw new \Exception("Curl error : ".curl_error($c));
		//$status  = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);
		
		return $content;
	}
}
