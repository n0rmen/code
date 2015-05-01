<?php
$host = "google.com";
$cmd = "ping ".escapeshellarg($host);
echo "<p>".$cmd."</p>";

/*
 * Display result
 * Put result code in $status
 */
passthru($cmd, $status); 
var_dump($status);

/*
 * Display result
 * Returns result last line
 * Put result code in $status
 */
$result = system($cmd, $status);
var_dump($result);
var_dump($status);

/*
 * Returns result
 */
$result = shell_exec($cmd);
var_dump($result);

/*
 * Returns result last line
 * Put result lines in array $output
 * Put result code in $status
 */
$result = exec($cmd, $output, $status);
var_dump($result);
var_dump($output);
var_dump($status);
