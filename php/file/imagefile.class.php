<?php

class ImageFile extends File{
	public $infos;
	public $width;
	public $height;
	public $mimetype;
	
	public function __construct($path){
		parent::__construct($path);
		
		$this->setInfos($path);
	}
	
	private function setInfos($path){
		$this->infos = getimagesize($path);
		$this->width = $this->infos[0];
		$this->height = $this->infos[1];
		$this->mimetype = $this->infos['mime'];
	}
	
	public function getMimetype(){
		if(empty($this->infos)) return parent::getMimetype();
		return $this->mimetype;
	}
	
	public function getDimensions(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return array('width' => $this->width, 'height' => $this->height);
	}
	
	public function resize($width, $height, $options=array()){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		if(empty($width)) $width = $this->width;
		if(empty($height)) $height = $this->height;
		
		$img = imagecreatetruecolor($width, $height);
		switch($this->mimetype){
			case "image/jpeg":
				$quality = empty($options['quality']) ? 75 : $options['quality'];
				$import = imagecreatefromjpeg($this->path);
				imagecopyresampled($img, $import, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
				imagejpeg($img, $this->path, $quality);
				break;
			case "image/png":
				$import = imagecreatefrompng($this->path);
				imagecopyresampled($img, $import, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
				imagepng($img, $this->path, $options['quality']);
				break;
			case "image/gif":
				$import = imagecreatefromgif($this->path);
				imagecopyresampled($img, $import, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
				imagegif($img, $this->path);
				break;
		}
		imagedestroy($img);
		
		$this->setInfos($this->path);
	}
	
	public function crop($width, $height, $options=array()){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		if(empty($width)) $width = $this->width;
		if(empty($height)) $height = $this->height;
		
		$src_x = $src_y = 0;
		if(!empty($options['align'])){
			if(preg_match("/left/", $options['align'])) $src_x = 0;
			else if(preg_match("/center/", $options['align'])) $src_x = (int) ($this->width - $width) / 2;
			else if(preg_match("/right/", $options['align'])) $src_x = (int) ($this->width - $width);
			if(preg_match("/top/", $options['align'])) $src_y = 0;
			else if(preg_match("/middle/", $options['align'])) $src_y = (int) ($this->height - $height) / 2;
			else if(preg_match("/bottom/", $options['align'])) $src_y = (int) ($this->height - $height);
		}		
		
		$img = imagecreatetruecolor($width, $height);
		switch($this->mimetype){
			case "image/jpeg":
				$quality = empty($options['quality']) ? 75 : $options['quality'];
				$import = imagecreatefromjpeg($this->path);
				imagecopyresampled($img, $import, 0, 0, $src_x, $src_y, $width, $height, $width, $height);
				imagejpeg($img, $this->path, $quality);
				break;
			case "image/png":
				$import = imagecreatefrompng($this->path);
				imagecopyresampled($img, $import, 0, 0, $src_x, $src_y, $width, $height, $width, $height);
				imagepng($img, $this->path, $options['quality']);
				break;
			case "image/gif":
				$import = imagecreatefromgif($this->path);
				imagecopyresampled($img, $import, 0, 0, $src_x, $src_y, $width, $height, $width, $height);
				imagegif($img, $this->path);
				break;
		}
		imagedestroy($img);
		
		$this->setInfos($this->path);
	}
	
	public function copy($path){
		if(parent::copy($path)) return new self($path);
		return false;
	}
}