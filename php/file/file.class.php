<?php

class File{
	public $path;
	public $name;
	public $dirname;
	
	public function __construct($path=null){
		if(empty($path)) throw new Exception("Undefined path");
		$this->setPath($path);
	}
	
	private function setPath($path){
		$this->path = $path;
		$this->name = basename($path);
		$this->dirname = dirname($path);
	}
	
	public function isFile(){
		return is_file($this->path);
	}
	
	public function getSize(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return filesize($this->path);
	}
	
	public function getTime(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return filemtime($this->path);
	}
	
	public function getOwnerId(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return fileowner($this->path);
	}
	
	public function setOwnerId($id){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return chown($this->path, (int) $id);
	}
	
	public function getGroupId(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return filegroup($this->path);
	}
	
	public function getPermissions(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return fileperms($this->path);
	}
	
	public function setPermissions($permissions){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return chmod($this->path, (int) $permissions);
	}
	
	public function getMimetype(){
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		return $finfo->file($this->path);
	}
	
	public function read(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return file_get_contents($this->path);
	}
	
	public function write($content){
		if(empty($this->path)) throw new FileDoesNotExistsException($this->path);
		return file_put_contents($this->path, $content);
	}
	
	public function append($content){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return file_put_contents($this->path, $content, FILE_APPEND);
	}
	
	public function rename($name){
		if(empty($name)) throw new Exception("Name is undefined");
		return $this->move($this->dirname.DIRECTORY_SEPARATOR.$name);
	}
	
	public function move($path){
		if(empty($path)) throw new Exception("Path is undefined");
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		if(rename($this->path, $path)){
			$this->setPath($path);
			return true;
		}
		return false;
	}
	
	public function copy($path){
		if(empty($path)) throw new Exception("Path is undefined");
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		if(copy($this->path, $path)) return new self($path);
		return false;
	}
	
	public function delete(){
		if(!is_file($this->path)) throw new FileDoesNotExistsException($this->path);
		return unlink($this->path);
	}
	
	public static function upload($file, $path, $options=array()){
		// to complete
		if(!move_uploaded_file($file['tmp_name'], $path)) throw new Exception("Impossible to upload file");
		return new self($path);
	}
	
	public static function upload($file, $path, $options=array()){
		if(empty($path)) throw new Exception("Undefined path");
		if(empty($file)) throw new Exception("Undefined file");
		if($file['error']) throw new UploadFileErrorException($file['error']);
		if(!is_uploaded_file($file['tmp_name'])) throw new UploadFileErrorException(UPLOAD_ERR_NO_FILE);
		
		if(!empty(self::$allowed_path_root) && !preg_match("/^".addslashes(self::$allowed_path_root)."/i", realpath(dirname($path)))) throw new UploadFileUnauthorizedPathException($path);
		
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mimetype =  $finfo->file($file['tmp_name']);
		if(isset($options['allowed_mimetypes'])) self::$allowed_mimetypes = array_merge(self::$allowed_mimetypes, array_flip($options['allowed_mimetypes']));
		if(!isset(self::$allowed_mimetypes[$mimetype])) throw new UploadFileInvalidFormatException();
		
		if(!move_uploaded_file($file['tmp_name'], $path)) throw new UploadFileErrorException();
		
		return new self($path);
	}
	
	public static function download($url, $path, $options=array()){
		if(!filter_var($url, FILTER_VALIDATE_URL)) throw new Exception("URL is required");
		if(empty($path)) throw new Exception("Path is undefined");
		if(!copy($url, $path)) throw new Exception("Impossible to download file");
		return new self($path);
	}
}

class FileDoesNotExistsException extends Exception{
	public function __construct($path=""){
		parent::__construct("File ".$path." does not exists");
	}
}

class UploadFileErrorException extends Exception{
	public function __construct($error){
		switch ($error){
			case UPLOAD_ERR_NO_FILE: $message = "Upload file undefined"; break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE: $message = "Exceeded filesize limit"; break;
			default: $message = "Error";
		}
		parent::__construct($message);
	}
}

class UploadFileInvalidFormatException extends Exception{
	public function __construct(){
		parent::__construct("Invalid file format or mimetype");
	}
}

class UploadFileUnauthorizedPathException extends Exception{
	public function __construct($path=""){
		parent::__construct("Unauthorized path ".$path);
	}
}
