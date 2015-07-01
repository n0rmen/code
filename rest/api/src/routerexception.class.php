<?php

class RouterBadRequestException extends Exception{
	public function __construct(){
		parent::_construct("Not Found", 400);
	}
}

class RouterNotFoundException extends Exception{
	public function __construct(){
		parent::__construct("Not Found", 404);
	}
}

class RouterMethodNotAllowedException extends Exception{
	public function __construct(){
		parent::__construct("Method Not Allowed", 405);
	}
}
