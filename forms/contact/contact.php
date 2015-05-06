<?php
if(isset($_POST['contact'])){
	try{
		if(empty($_POST['name'])) throw new Exception("Vous devez entrer votre nom");
		if(empty($_POST['email']) || !filter_var(stripslashes($_POST['email']), FILTER_VALIDATE_EMAIL)) throw new Exception("Vous devez entrer une adresse email valide");
		if(empty($_POST['subject'])) throw new Exception("Vous devez entrer un objet");
		if(empty($_POST['text'])) throw new Exception("Vous devez entrer un message");
		
		$name = trim(strip_tags(stripslashes($_POST['name'])));
		$email = stripslashes($_POST['email']);
		$object = trim(strip_tags(stripslashes($_POST['subject'])));
		$text = trim(strip_tags(stripslashes($_POST['text'])));
		$to = "madislak@yahoo.fr";
		$domain = "cyrilwebdesign.com";
		
		$subject = htmlentities($object, ENT_QUOTES, "UTF-8").(empty($domain) ? "" : " (via ".$domain.")");
		$header = "From:".htmlentities($name, ENT_QUOTES, "UTF-8")." <".$email.">\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/html; charset=utf-8\r\n";
		$message = nl2br(htmlentities($text, ENT_QUOTES, "UTF-8"));
		if(!@mail($to, $subject, $message, $header, null)) throw new Exception("Impossible d'envoyer le message");
		
		$response = (object) array(
			"success" => true,
			"message" => "Votre message a bien &eacute;t&eacute; envoy&eacute;"
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
