<?php
/**
 * Soundcloud API
 * 
 * @author Cyril Vermande (cyril [at] cyrilwebdesign.com)
 * @copyright 2015 Cyril Vermande
 * @license http://opensource.org/licenses/mit MIT
 * @link https://developers.soundcloud.com/docs/api/reference
 */

/**
 * Class Soundcloud
 *
 * Example : $sc = new Soundcloud(array('client_id' => "YOUR_CLIENT_ID"));
 */
class Soundcloud{
	/**
	 * @var string $api_key API key provided by Google
	 */
	protected $api_key;
	
	/**
	 * Constructor
	 *
	 * @param array $params	API parameters : api_key
	 *
	 * @throw Exception
	 */
	function __construct($params=array()){
		if(empty($params['client_id'])) throw Exception("Missing client ID");
		$this->api_key = $params['client_id'];
	}
	
	/**
	 * Get a Soundcloud track
	 * @link https://developers.soundcloud.com/docs/api/reference#tracks
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getTrack($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)){
			$result = $this->get("https://api.soundcloud.com/resolve", array('url' => $uri));
		}
		else{
			$result = $this->get("https://api.soundcloud.com/tracks/".$uri);
		}
		
		if(empty($result->kind) || $result->kind !== "track") throw new Exception("Resource not found");
		return $result;
	}
	
	/**
	 * Search Soundcloud tracks
	 * @link https://developers.soundcloud.com/docs/api/reference#tracks
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchTracks($q, $options=array()){
		$params = array('q' => urlencode($q));
		
		$result = $this->get("https://api.soundcloud.com/tracks/", $params);
		
		return $result;
	}
	
	/**
	 * Get a Soundcloud playlist
	 * @link https://developers.soundcloud.com/docs/api/reference#playlists
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getPlaylist($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)){
			$result = $this->get("https://api.soundcloud.com/resolve", array('url' => $uri));
		}
		else{
			$result = $this->get("https://api.soundcloud.com/playlists/".$uri);
		}
		
		if(empty($result->kind) || $result->kind !== "playlist") throw new Exception("Resource not found");
		return $result;
	}
	
	/**
	 * Get the tracks of a Soundcloud playlist
	 * @link https://developers.soundcloud.com/docs/api/reference#playlists
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function getPlaylistTracks($uri, $options=array()){
		$result = $this->getPlaylist($uri)->tracks;
		
		return $result;
	}
	
	/**
	 * Search Soundcloud playlists
	 * @link https://developers.soundcloud.com/docs/api/reference#playlists
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchPlaylists($q, $options=array()){
		$params = array('q' => urlencode($q));
		
		$result = $this->get("https://api.soundcloud.com/playlists/", $params);
		
		return $result;
	}
	
	/**
	 * Get a Soundcloud user
	 * @link https://developers.soundcloud.com/docs/api/reference#users
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getUser($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)){
			$result = $this->get("https://api.soundcloud.com/resolve", array('url' => $uri));
		}
		else{
			$result = $this->get("https://api.soundcloud.com/users/".$uri);
		}
		
		if(empty($result->kind) || $result->kind !== "user") throw new Exception("Resource not found");
		return $result;
	}
	
	/**
	 * Get the playlists of a Soundcloud user
	 * @link https://developers.soundcloud.com/docs/api/reference#users
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 * @throw Exception
	 */
	public function getUserPlaylists($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)){
			$result = $this->get("https://api.soundcloud.com/resolve", array('url' => $uri));
			if(empty($result->kind) || $result->kind !== "user") throw new Exception("Resource not found");
			$uri = $result->id;
		}
		
		$result = $this->get("https://api.soundcloud.com/users/".$uri."/playlists");
		
		return $result;
	}
	
	/**
	 * Get the tracks of a Soundcloud user
	 * @link https://developers.soundcloud.com/docs/api/reference#users
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 * @throw Exception
	 */
	public function getUserTracks($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)){
			$result = $this->get("https://api.soundcloud.com/resolve", array('url' => $uri));
			if(empty($result->kind) || $result->kind !== "user") throw new Exception("Resource not found");
			$uri = $result->id;
		}
		
		$result = $this->get("https://api.soundcloud.com/users/".$uri."/tracks");
		
		return $result;
	}
	
	/**
	 * Search Soundcloud users
	 * @link https://developers.soundcloud.com/docs/api/reference#users
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchUsers($q, $options=array()){
		$params = array('q' => urlencode($q));
		
		$result = $this->get("https://api.soundcloud.com/users/", $params);
		
		return $result;
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
		$params['client_id'] = $this->api_key;
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
		$params['client_id'] = $this->api_key;
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
