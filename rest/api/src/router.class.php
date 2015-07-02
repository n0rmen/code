<?php
/**
 * Router
 * 
 * @author Cyril Vermande (cyril [at] cyrilwebdesign.com)
 * @copyright 2015 Cyril Vermande
 * @license http://opensource.org/licenses/mit MIT
 */

/**
 * Class Router
 *
 * Example : $router = new Router("conf/routing.json");
 */
class Router{
	
	/**
	 * @var object[] $routes List of available routes
	 */
	public $routes = array();
	
	/**
	 * Register a route
	 *
	 * @param string $methods HTTP method to match (POST, GET, PUT, PATCH or DELETE)
	 * @param string $pattern Regex pattern to match
	 * @param string $controller Controller to call (Class::method)
	 * 
	 * @throws Exception if one the arguments is empty
	 */
	public function addRoute($methods, $pattern, $controller){
		if(empty($methods)) throw new Exception("Undefined method");
		if(empty($pattern)) throw new Exception("Undefined pattern");
		if(empty($controller)) throw new Exception("Undefined controller");
		
		$this->routes[] = (object) array('methods' => $methods, 'pattern' => $pattern, 'controller' => $controller);
	}
	
	/**
	 * Register multiples routes
	 *
	 * @param object[] $routes List of the routes to register
	 */
	public function addRoutes(Array $routes){
		foreach($routes as $route){
			if(is_array($route)) $route = (object) $route;
			if(empty($route->methods) || empty($route->pattern) || empty($route->controller)) continue;
			
			$this->routes[] = $route;
		}
	}
	
	/**
	 * Start the router
	 *
	 * @param string $methods The request HTTP method (POST, GET, PUT, PATCH or DELETE)
	 * @param string $uri The request URI to test
	 * @param string $params The request get and/or post data
	 * 
	 * @return array|object The result send by the controller
	 * @throws RouterBadRequestException if $uri is empty
	 * @throws RouterMethodNotAllowedException if $method is not allowed for the route
	 * @throws RouterNotFoundException if $uri does not matche any route
	 */
	public function handle($method, $uri, $params=array()){
		if(empty($uri)) throw new RouterBadRequestException;
		
		parse_str(parse_url($uri, PHP_URL_QUERY), $query);
		$params = array_merge($query, $params);
		$uri = parse_url($uri, PHP_URL_PATH);
		
		foreach($this->routes as $route){
			if(preg_match("/".$route->pattern."/i", $uri, $matches)){
				if(preg_match("/^(".$route->methods.")$/i", $method)){
					array_shift($matches);
					array_push($matches, $params);
					list($class, $static_method) = explode("::", $route->controller);
					$result = forward_static_call_array(array($class, $static_method), $matches);
					return $result;
				}
				
				throw new RouterMethodNotAllowedException;
			}
		}
		
		throw new RouterNotFoundException;
	}
	
	/**
	 * Constructor
	 *
	 * @param string $routingFilepath URL of the routing file (JSON)
	 */
	public function __construct($routingFilepath=null){
		if(!empty($routingFilepath)){
			$content = file_get_contents($routingFilepath);
			$routes = json_decode($content);
			$this->addRoutes($routes);
		}
	}
}
