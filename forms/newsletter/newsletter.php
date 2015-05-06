<?php
ini_set("display_errors", 0);
include "libraries/mailchimp/Mailchimp.php";

$api_key = "b6c0efc88869ff082d6c2c2e3602d757-us3";
$list_id = "2919049cb7"; // Newsletter
$list_id = "2868371c15"; // Test

if(isset($_POST['subscribe'])){
	try{
		if(empty($_POST['email']) || !filter_var(stripslashes($_POST['email']), FILTER_VALIDATE_EMAIL)) throw new Exception("Vous devez entrer une adresse email valide");
		
		$email = stripslashes($_POST['email']);
	
		$mc = new Mailchimp($api_key, array('ssl_verifypeer' => false));
		if(!$mc->lists->subscribe($list_id, array("email" => $email), $merge_vars=null, $email_type='html', $double_optin=false)) throw new Exception("Inscription impossible");
		
		$response = (object) array(
			"success" => true,
			"message" => "Vous êtes maintenant inscrit(e) à la newsletter"
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
