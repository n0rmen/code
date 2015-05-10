<?php
require_once('pclzip.lib.php');

$dir_path = dirname(__FILE__).DIRECTORY_SEPARATOR;

$to_extract = array();
$rep = dir($dir_path);
while ($nametmp = $rep->read()) {
	$key = str_replace(".", "__", $nametmp);
	if(isset($_POST[$key]) && $_POST[$key] == "on") $to_extract[] = $nametmp;
}
$rep->close();

if(sizeof($to_extract) > 0){
	$dirname = empty($_POST['dirname']) ? "." : $_POST['dirname'];
	foreach($to_extract as $filepath){
		$zip = new PclZip($filepath);
		$zip->extract(PCLZIP_OPT_PATH, $dirname);
		echo "<p>".$filepath." : extraction succeed</p>";
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
Extract in folder : <input type="text" name="dirname"><br>
<input type="submit" value="Extract">
</form>
<script type="text/javascript">
	function toogleCheckAll(element){
		var checkboxes = document.querySelectorAll('input[type=checkbox]');
		for(var i=0,l=checkboxes.length; i<l; i++) {
			checkboxes[i].checked = element.checked;
		}
	}
</script>
