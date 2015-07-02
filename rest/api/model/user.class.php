<?php

class User{
	public $id;
	public $login;
	private $password;
	public $name;
	public $roles;
	
	public static function get($id){
		if(empty($id) || $id != 1) throw new Exception("wrong id");
		$user = new self;
		$user->id = 1;
		$user->login = "admin";
		$user->name = "Cyril";
		$user->roles = array('ADMIN', 'USER');
		return $user;
	}
	
	public static function getAll(){
		$user = new self;
		$user->id = 1;
		$user->login = "admin";
		$user->name = "Cyril";
		$user->roles = array('ADMIN', 'USER');
		return array($user);
	}
}
