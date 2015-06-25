<?php
/**
 * Youtube API
 * 
 * @author Cyril Vermande (cyril [at] cyrilwebdesign.com)
 * @copyright 2015 Cyril Vermande
 * @license http://opensource.org/licenses/mit MIT
 * @link https://developers.google.com/youtube/v3/docs/
 */

/**
 * Class Youtube
 *
 * Example : $yt = new Youtube(array('api_key' => "YOUR_API_KEY"));
 */
class Youtube{
	/**
	 * @var string $api_key API key provided by Google
	 */
	protected $api_key;
	
	/**
	 * Constructor
	 *
	 * @param array $params	API parameters : api_key
	 */
	function __construct($params){
		if(empty($params['api_key'])) throw Exception("Missing API key");
		
		$this->api_key = $params['api_key'];
	}
	
	/**
	 * Get a Youtube video
	 * @link https://developers.google.com/youtube/v3/docs/videos
	 * 
	 * @param string $id Id of the video
	 * @param array $options Array of options
	 * 
	 * @return object A resource represents a YouTube video
	 */
	public function getVideo($id, $options=array()){
		$params = array('id' => $id, 'part' => 'id, snippet');
		if(isset($options['part'])) $params['part'] = $options['part'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/videos", $params);
		
		if($result->pageInfo->totalResults === 0) return null;
		return $result->items[0];
	}
	
	/**
	 * Search Youtube videos
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[] An array of resources represent YouTube videos
	 */
	public function searchVideos($q, $options=array()){
		$options['type'] = "video";
		return $this->search($q, $options);
	}
	
	/**
	 * Get a Youtube playlist
	 * @link https://developers.google.com/youtube/v3/docs/playlists
	 * 
	 * @param string $id Id of the playlist
	 * @param array $options Array of options
	 * 
	 * @return object A resource represents a YouTube playlist
	 */
	public function getPlaylist($id, $options=array()){
		$params = array('id' => $id, 'part' => 'id, snippet');
		if(isset($options['part'])) $params['part'] = $options['part'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/playlists", $params);
		
		if($result->pageInfo->totalResults === 0) return null;
		return $result->items[0];
	}
	
	/**
	 * Get the videos of a Youtube playlist
	 * @link https://developers.google.com/youtube/v3/docs/playlistItems
	 * 
	 * @param string $id Id of the playlist
	 * @param string $pageToken Token of the page
	 * @param array $options Array of options
	 * 
	 * @return object[] An array of resources represent YouTube videos
	 */
	public function getPlaylistItems($id, $pageToken=false, $options=array()){
		$params = array('playlistId' => $id, 'part' => 'id, snippet');
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		if(is_string($pageToken)) $params['pageToken'] = $pageToken;
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/playlistItems", $params);
		
		return $result;
	}
	
	/**
	 * Search Youtube playlists
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[] An array of resources represent YouTube playlists
	 */
	public function searchPlaylists($q, $options=array()){
		$options['type'] = "playlist";
		return $this->search($q, $options);
	}
	
	/**
	 * Get a Youtube channel
	 * @link https://developers.google.com/youtube/v3/docs/channels
	 * 
	 * @param string $id Id of the channel
	 * @param array $options Array of options
	 * 
	 * @return object A resource represents a YouTube channel
	 */
	public function getChannel($id, $options=array()){
		$params = array('id' => $id, 'part' => 'id, snippet');
		if(isset($options['part'])) $params['part'] = $options['part'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/channels", $params);
		
		if($result->pageInfo->totalResults === 0) return null;
		return $result->items[0];
	}
	
	/**
	 * Search Youtube channels
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[] An array of resources represent YouTube channels
	 */
	public function searchChannels($q, $options=array()){
		$options['type'] = "channel";
		return $this->search($q, $options);
	}
	
	/**
	 * Search Youtube resources
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[] An array of resources
	 */
	public function search($q, $options=array()){
		$params = array('q' => $q, 'part' => 'id, snippet');
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['type'])) $params['type'] = $options['type'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/search", $params);
		
		return $result->items;
	}
	
	/**
	 * GET the Id of a Youtube video from it's URL
	 * 
	 * @param string $url The resource URL
	 * 
	 * @return string|null The video Id
	 */
	public static function getVideoIdFromUrl($url) {
		$parse_url = parse_url($url);
		$path = $parse_url['path'];
		if(strpos($url, 'youtube.com')){
			if(strpos($url, 'embed')){
				return substr($path, 7);
			}
			else{
				$query = $parse_url['query'];
				parse_str($query, $params);
				return $params['v'];
			}
		}
		else if(strpos($url, 'youtu.be')){
			return substr($path, 1);
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
	 */
	public function get($url, $params) {
		$params['key'] = $this->api_key;
		$url .= (!strpos($url, '?') ? '?' : '').http_build_query($params);
		$result =  self::_curl($url, "GET");
		
		return json_decode($result);
	}
	
	/**
	 * POST request
	 * 
	 * @param string $url The resource URL
	 * @param array $params Array of parameters
	 * 
	 * @return object Decoded JSON response
	 */
	public function post($url, $params) {
		$params['key'] = $this->api_key;
		$url .= (!strpos($url, '?') ? '?' : '&')."key=".$this->api_key;
		$result =  self::_curl($url, "GET", $params);
		
		return json_decode($result);
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
	 */
	private static function _curl($url, $method='GET', $params=array(), $header=array('Content-Type: application/x-www-form-urlencoded')) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		if($method=='POST') {
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params, null,'&'));
		}
		if (!empty($header)) {
			curl_setopt($c, CURLOPT_HTTPHEADER, $header);
		}
		
		$content = curl_exec($c);
		
		if(curl_errno($c)) throw new \Exception("Curl error : ".curl_error($c));
		//$status  = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);
		
		return $content;
	}
}