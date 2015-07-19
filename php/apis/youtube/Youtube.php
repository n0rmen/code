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
	 *
	 * @throw Exception
	 */
	function __construct($params=array()){
		if(empty($params['api_key'])) throw new Exception("Missing API key");
		$this->api_key = $params['api_key'];
	}
	
	/**
	 * Resolve a Youtube URL
	 * @link https://developers.google.com/youtube/v3/docs
	 * 
	 * @param string $url URL of the resource
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function resolve($url){
		if(!filter_var($url, FILTER_VALIDATE_URL) || (strpos($url, "youtube.com") === false && strpos($url, "youtu.be") === false)) throw new Exception("Invalid parameter (Youtube URL required)");
		
		$parse_url = parse_url($url);
		$host = $parse_url['host'];
		$path = $parse_url['path'];
		
		if(preg_match("/^\/channel/", $path)){
			$resourceId = substr($path, 9);
			$result = $this->getChannel($resourceId);
		}
		else if(preg_match("/^\/embed/", $path)){
			$resourceId = substr($path, 7);
			$result = $this->getVideo($resourceId);
		}
		else if(preg_match("/youtu.be/", $host)){
			$resourceId = substr($path, 1);
			$result = $this->getVideo($resourceId);
		}
		else{
			$query = $parse_url['query'];
			parse_str($query, $params);
			$resourceId = $params['v'];
			$result = $this->getVideo($resourceId);
		}
		
		return $result;
	}
	
	/**
	 * Get a Youtube video
	 * @link https://developers.google.com/youtube/v3/docs/videos
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getVideo($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getVideoIdFromUrl($uri);
		
		$params = array('id' => $uri, 'part' => "id, snippet");
		if(isset($options['part'])) $params['part'] = $options['part'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/videos", $params);
		
		if($result->pageInfo->totalResults === 0) throw new Exception("Resource not found");
		return $result->items[0];
	}
	
	/**
	 * Search Youtube videos
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchVideos($q, $options=array()){
		$params = array('type' => "video");
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		
		$result = $this->search($q, $params);
		
		return $result;
	}
	
	/**
	 * Get a Youtube playlist
	 * @link https://developers.google.com/youtube/v3/docs/playlists
	 * 
	 * @param mixed $uri URI of the playlist
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getPlaylist($uri, $options=array()){
		$params = array('id' => $uri, 'part' => 'id, snippet, contentDetails, player');
		if(isset($options['part'])) $params['part'] = $options['part'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/playlists", $params);
		
		if($result->pageInfo->totalResults === 0) throw new Exception("Resource not found");
		return $result->items[0];
	}
	
	/**
	 * Get the videos of a Youtube playlist
	 * @link https://developers.google.com/youtube/v3/docs/playlistItems
	 * 
	 * @param mixed $uri URI of the resource
	 * @param string $pageToken Token of the page
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function getPlaylistVideos($uri, $pageToken=false, $options=array()){
		$params = array('playlistId' => $uri, 'part' => "id, snippet");
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		if(is_string($pageToken)) $params['pageToken'] = $pageToken;
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/playlistItems", $params);
		
		return $result->items;
	}
	
	/**
	 * Search Youtube playlists
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchPlaylists($q, $options=array()){
		$params = array('type' => "playlist");
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		
		$result = $this->search($q, $options);
		
		return $result;
	}
	
	/**
	 * Get a Youtube channel
	 * @link https://developers.google.com/youtube/v3/docs/channels
	 * 
	 * @param mixed $uri URI of the resource
	 * @param array $options Array of options
	 * 
	 * @return object
	 * @throw Exception
	 */
	public function getChannel($uri, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getChannelIdFromUrl($uri);
		
		$params = array('id' => $uri, 'part' => "id, snippet");
		if(isset($options['part'])) $params['part'] = $options['part'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/channels", $params);
		
		if($result->pageInfo->totalResults === 0) throw new Exception("Resource not found");
		return $result->items[0];
	}
	
	/**
	 * Get the playlists of a Youtube channel
	 * @link https://developers.google.com/youtube/v3/docs/playlists
	 * 
	 * @param mixed $uri URI of the resource
	 * @param string $pageToken Token of the page
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function getChannelPlaylists($uri, $pageToken=false, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getChannelIdFromUrl($uri);
		
		$params = array('channelId' => $uri, 'part' => "id, snippet");
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		if(is_string($pageToken)) $params['pageToken'] = $pageToken;
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/playlists", $params);
		
		return $result->items;
	}
	
	/**
	 * Get the videos of a Youtube channel
	 * @link https://developers.google.com/youtube/v3/docs/videos
	 * 
	 * @param mixed $uri URI of the resource
	 * @param string $pageToken Token of the page
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function getChannelVideos($uri, $pageToken=false, $options=array()){
		if(filter_var($uri, FILTER_VALIDATE_URL)) $uri = $this->getChannelIdFromUrl($uri);
		
		$params = array('type' => "video", 'part' => "id, snippet", 'channelId' => $uri);
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/search", $params);
		
		return $result->items;
	}
	
	/**
	 * Search Youtube channels
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function searchChannels($q, $options=array()){
		$params = array('type' => "channel");
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		
		$result = $this->search($q, $params);
		
		return $result;
	}
	
	/**
	 * Search Youtube resources
	 * @link https://developers.google.com/youtube/v3/docs/search
	 * 
	 * @param string $q The terms to search for
	 * @param array $options Array of options
	 * 
	 * @return object[]
	 */
	public function search($q, $options=array()){
		$params = array('q' => $q, 'part' => "id, snippet");
		if(isset($options['part'])) $params['part'] = $options['part'];
		if(isset($options['type'])) $params['type'] = $options['type'];
		if(isset($options['maxResults'])) $params['maxResults'] = $options['maxResults'];
		
		$result = $this->get("https://www.googleapis.com/youtube/v3/search", $params);
		
		return $result->items;
	}
	
	/**
	 * GET a Youtube video ID from a URL
	 * 
	 * @param string $url The resource URL
	 * 
	 * @return string
	 * @throw Exception
	 */
	private function getVideoIdFromUrl($url){
		if(!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception("Invalid parameter (URL required)");
		
		$parse_url = parse_url($url);
		$path = $parse_url['path'];
		if(strpos($url, "youtube.com")){
			if(strpos($url, "embed")){
				return substr($path, 7);
			}
			else{
				$query = $parse_url['query'];
				parse_str($query, $params);
				return $params['v'];
			}
		}
		else if(strpos($url, "youtu.be")){
			return substr($path, 1);
		}
		
		throw new Exception("Invalid parameter (Youtube video URL required)");
	}
	
	/**
	 * GET a Youtube playlist ID from a URL
	 * 
	 * @param string $url The resource URL
	 * 
	 * @return string
	 * @throw Exception
	 */
	private function getPlaylistIdFromUrl($url){
		throw new Exception("NOT IMPLEMENTED YET");
		if(!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception("Invalid parameter (URL required)");
		
		$parse_url = parse_url($url);
		
		throw new Exception("Invalid parameter (Youtube playlist URL required)");
	}
	
	/**
	 * GET a Youtube channel ID from a URL
	 * 
	 * @param string $url The resource URL
	 * 
	 * @return string
	 * @throw Exception
	 */
	private function getChannelIdFromUrl($url){
		if(!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception("Invalid parameter (URL required)");
		
		$parse_url = parse_url($url);
		$host = $parse_url['host'];
		$path = $parse_url['path'];
		if(strpos($url, "youtube.com") && preg_match("/^\/channel/", $path)){
			return substr($path, 9);
		}
		
		throw new Exception("Invalid parameter (Youtube channel URL required)");
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
		$params['key'] = $this->api_key;
		$url .= (strpos($url, "?") === false ? "?" : "&").http_build_query($params);
		
		$json = $this->_curl($url, "GET");
		$result = json_decode($json);
		
		if(isset($result->error)){
			throw new Exception($result->error->message." (".$result->error->errors[0]->reason.")", $result->error->code);
		}
		
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
		$params['key'] = $this->api_key;
		$url .= (strpos($url, "?") === false ? "?" : "&")."key=".$this->api_key;
		
		$json =  $this->_curl($url, "POST", $params);
		$result = json_decode($json);
		
		if(isset($result->error)){
			throw new Exception($result->error->message." (".$result->error->errors[0]->reason.")", $result->error->code);
		}
		
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
