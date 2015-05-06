<?php
$start_time = microtime(true);

class FormException extends Exception{
	private $errors = array();
	
	public function __construct($errors){
		foreach($errors as $error){
			$this->errors[] = (object) array("message" => $error);
		}
	}
	
	public function getErrors(){
		return $this->errors;
	}
}

if(isset($_POST['validation'])){
	try{
		$errors = array();
		if(empty($_POST['name'])) $errors[] = "name undefined";
		if(empty($_POST['email'])) $errors[] = "email undefined";
		if(sizeof($errors) > 0) throw new FormException($errors);
		
		$response = (object) array(
			"success" => true,
			"message" => "Ok",
			"result" => (object) array(
				"_POST" => $_POST,
				"_FILES" => $_FILES
			)
		);
		die(json_encode($response));
	}
	catch(FormException $e){
		$response = (object) array(
			"success" => false,
			"message" => $e->getMessage(),
			"errors" => $e->getErrors()
		);
		die(json_encode($response));
	}
	catch(Exception $e){
		$response = (object) array(
			"success" => false,
			"message" => $e->getMessage()
		);
		die(json_encode($response));
	}
}
