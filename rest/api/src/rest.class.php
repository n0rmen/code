<?php

class Rest{
	public $router;
	
	public function __construct(){
		$this->router = new Router("conf/routing.json");
	}
	
	public function post($resource, $params=array()){
		return $this->router->handle("POST", $resource, $params);
	}
	
	public function get($resource, $params=array()){
		return $this->router->handle("GET", $resource, $params);
	}
	
	public function put($resource, $params=array()){
		return $this->router->handle("PUT", $resource, $params);
	}
	
	public function delete($resource){
		return $this->router->handle("DELETE", $resource);
	}
}
