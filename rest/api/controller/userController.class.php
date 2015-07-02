<?php

class UserController{
	public static function createAction($params){
		return "User created : ".$params['name'];
	}
	
	public static function readAction($id){
		return User::get($id);
	}
	
	public static function updateAction($id, $params){
		return "User updated : ".$params['name'];
	}
	
	public static function deleteAction($id){
		return "User deleted";
	}
	
	public static function listAction(){
		return User::getAll();
	}
}
