<?php
if(!class_exists("getID3")) require "getid3/getid3.php";

class AudioFile extends File{
	public $tags;
	
	public function __construct($path){
		parent::__construct($path);
		
		$id3 = new getID3();
		$id3->setOption(array('encoding' => 'utf8'));
		$this->tags = $id3->analyze($path);
	}
	
	public function getMimetype(){
		if(empty($this->tags)) return parent::getMimetype();
		return $this->tags['mime_type'];
	}
	
	public function getSize(){
		if(empty($this->tags)) return parent::getSize();
		return $this->tags['filesize'];
	}
	
	public function getTitle(){
		return empty($this->tags['tags']['id3v2']['title'][0]) ? (empty($this->tags['tags']['id3v1']['title'][0]) ? null : $this->tags['tags']['id3v1']['title'][0]) : $this->tags['tags']['id3v2']['title'][0];
	}
	
	public function getArtist(){
		return empty($this->tags['tags']['id3v2']['artist'][0]) ? (empty($this->tags['tags']['id3v1']['artist'][0]) ? null : $this->tags['tags']['id3v1']['artist'][0]) : $this->tags['tags']['id3v2']['artist'][0];
	}
	
	public function getGenre(){
		return empty($this->tags['tags']['id3v2']['genre'][0]) ? null : $this->tags['tags']['id3v2']['genre'][0];
	}
	
	public function getAlbum(){
		return empty($this->tags['tags']['id3v2']['album'][0]) ? (empty($this->tags['tags']['id3v1']['album'][0]) ? null : $this->tags['tags']['id3v1']['album'][0]) : $this->tags['tags']['id3v2']['album'][0];
	}
	
	public function getTrackNumber(){
		return empty($this->tags['tags']['id3v2']['track_number'][0]) ? (empty($this->tags['tags']['id3v1']['track'][0]) ? 0 : $this->tags['tags']['id3v1']['track'][0]) : (int) $this->tags['tags']['id3v2']['track_number'][0];
	}
	
	public function getRate(){
		return (int) $this->tags['audio']['sample_rate'];
	}
	
	public function getBitrate(){
		return (int) $this->tags['audio']['bitrate'];
	}
	
	public function getDuration(){
		return (int) $this->tags['playtime_seconds'];
	}
	
	public function isLossless(){
		return $this->tags['audio']['lossless'] && true;
	}
	
	public function encode($options=array()){
		$bitrate = empty($options['bitrate']) ? 128 : (int) $options['bitrate'];
		
		switch($this->getMimetype()){
			case "audio/wav":
			case "audio/wave":
			case "audio/x-wave":
				$new_path = preg_replace("/.wav$/", ".mp3", $this->path);
				$result = exec("lame -b ".$bitrate." ".escapeshellarg($this->path)." ".escapeshellarg($new_path), $output, $status);
				break;
			case "audio/mpeg":
				$new_path = preg_replace("/.mp3$/", "_".$bitrate."kbps.mp3", $this->path);
				$result = exec("lame -b ".$bitrate." ".escapeshellarg($this->path)." ".escapeshellarg($new_path), $output, $status);
				break;
			case "audio/mp4":
				$new_path = preg_replace("/.m4a$/", ".mp3", $this->path);
				$result = exec("mplayer -ao pcm:file=tmp.wav ".escapeshellarg($this->path), $output, $status);
				$result = exec("lame -b ".$bitrate." tmp.wav ".escapeshellarg($new_path), $output, $status);
				$result = exec("rm -f tmp.wav", $output, $status);
				break;
			case "audio/x-ms-wma":
				$new_path = preg_replace("/.wma$/", ".mp3", $this->path);
				$result = exec("mplayer -vo null -vc dummy -af resample=44100 -ao pcm -ao pcm:file=tmp.wav ".escapeshellarg($this->path), $output, $status);
				$result = exec("lame -b ".$bitrate." tmp.wav ".escapeshellarg($new_path), $output, $status);
				$result = exec("rm -f tmp.wav", $output, $status);
				break;
			case "audio/flac":
			case "audio/x-flac":
				$new_path = preg_replace("/.flac$/", ".mp3", $this->path);
				$result = exec("flac -cd ".escapeshellarg($this->path)." | lame -b ".$bitrate." - ".escapeshellarg($new_path), $output, $status);
				break;
		}
		
		return new self($new_path);
	}
}
