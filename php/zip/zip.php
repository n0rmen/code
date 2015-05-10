<?php
require_once('pclzip.lib.php');

$dir_path = dirname(__FILE__).DIRECTORY_SEPARATOR;

if(!empty($_POST['filename'])){
	$path_to_add = array();
	$rep = dir($dir_path);
	while($nametmp = $rep->read()){
		$key = str_replace(".", "__", $nametmp);
		if(isset($_POST[$key]) && $_POST[$key] == "on") $path_to_add[] = $nametmp;
	}
	$rep->close();
	
	if(sizeof($path_to_add) > 0){
		$zip = new PclZip($_POST['filename'].".zip");
		$zip->create($path_to_add);
		if(is_file($_POST['filename'].".zip")) echo "<p>Archive created</p>";
	}
	else{
		echo "<p>No file to add to archive</p>";
	}
}
?>
<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
<input type="checkbox" onchange="toogleCheckAll(this);">&nbsp;Cocher tout<br>
<?php
$dirnames = array();
$rep = dir($dir_path);
while($nametmp = $rep->read()){
	if($nametmp != "." && $nametmp != ".." && $nametmp != "pclzip.lib.php" && $nametmp != "zip.php" && $nametmp != "unzip.php"){
		$key = str_replace(".", "__", $nametmp);
		if(is_dir($dir_path.$nametmp)) echo "<input type=\"checkbox\" name=\"".$key."\"> <label><strong>[DIR] ".$nametmp."</strong></label><br>";
		else echo "<input type=\"checkbox\" name=\"".$key."\"> <label>".$nametmp."</label><br>";
	}
}
$rep->close();
?>
<input type="checkbox" onchange="toogleCheckAll(this);">&nbsp;Cocher tout<br>
Archive name : <input type="text" name="filename" required><br>
<input type="submit" value="Zip">
</form>
<script type="text/javascript">
	function toogleCheckAll(element){
		var checkboxes = document.querySelectorAll('input[type=checkbox]');
		for(var i=0,l=checkboxes.length; i<l; i++) {
			checkboxes[i].checked = element.checked;
		}
	}
</script>
