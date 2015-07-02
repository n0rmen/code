<?php
/**
 * @file
 * @author : Cyril Vermande
 * 
 * Class ApiKey
*/

/**
 * ApiKey
 */
class ApiKey{
	public $id;
	public $key;
	public $domain;
	public $user_id;
	public $last_connection_date;
	
	public static function get($key){
		global $dbh;
		$sql = "SELECT * FROM storm_apikeys WHERE `key`='".addslashes($key)."';";
		$query = $dbh->query($sql);
		$query->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $query->fetch();
	}
	
	public function update_connection_date(){
		global $dbh;
		$sql = "UPDATE storm_apikeys SET last_connection_date='".date("Y-m-d H:i:s")."';";
		return $dbh->exec($sql);
	}
	
	public function verify_referer($referer){
		return $referer === $this->domain;
	}
}
