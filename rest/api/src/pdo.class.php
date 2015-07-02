<?php
/**
 * @file pdo.inc.php
 * @author Cyril Vermande
 * @copyright All rights reserved 2014 Cyril Vermande
 * 
 * PDO connection
 */

$ini_filename = API_ROOT."conf".DIRECTORY_SEPARATOR."pdo.ini";

try{
	if(!is_readable($ini_filename)) throw new PDOException ("Error: configuration file not found");
		$params = parse_ini_file($ini_filename);
		
		if(!isset($params['server'])
			|| !isset($params['username'])
			|| !isset($params['password'])
			|| !isset($params['dbname'])) throw new PDOException ("Error: configuration parameter is missing");
		$dsn = "mysql:host=".$params['server'].";dbname=".$params['dbname'].";charset=utf8";
		$username = $params['username'];
		$password = $params['password'];
		$options = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
		
		$dbh = new PDO($dsn, $username, $password, $options);
}
catch (PDOException $e){
	die($e->getMessage());
}
