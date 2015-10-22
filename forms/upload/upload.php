<?php
session_start();

require "../../../utils/form/Form.php";
require "../../../utils/form/UploadForm.php";
require "../../../utils/utils/Utils.php";
require "../../../utils/file/File.php";

if(isset($_POST['upload'])){
	var_dump($_FILES);
	$form = UploadForm::createFromRequest();
	$form->setOptions(array(
		'destinationPath' => "tmp/",
		'allowedMimeTypes' => array('image/png', 'image/jpeg'),
		'allowedSize' => array('max' => 1000000, 'min' => 1000),
		'overwrite' => false
	));
	$form->addRule('filename', 'timestamp');
	if($form->isValid() && $file = $form->upload()){
		var_dump($file);
	}
	else{
		var_dump($form->errors);
	}
}
else{
	$form = new UploadForm('upload-form');
}

?>
<script src="//modernizr.com/downloads/modernizr-2.6.2.js"></script>
<script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="../form/submitform.js"></script>

<form id="upload-form" class="customform uploadform" action="upload.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="form_id" value="<?php echo $form->id ?>">
	<input type="hidden" name="token" value="<?php echo $form->generateToken() ?>">
	<label for="upload-file">File</label><input id="upload-file" type="file" name="file" required><br>
	<button type="submit">Upload</button>
	<div id="upload-form-message"></div>
</form>
<script type="text/javascript">
$(function() {
	/*
	$('#upload-form').submit(function(e){
		submitForm(e, function(response){
			if(response.success){
				$('#upload-form-message').html("<p class=\"message success\">" + response.message + "</p>");
				e.target.reset();
			}
			else{
				$('#upload-form-message').html("<p class=\"message error\">" + response.message + "</p>");
			}
		});
	});
	*/
});
</script>