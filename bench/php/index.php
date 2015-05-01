<?php
require "bench.class.php";

function method1($arg){
	return empty($arg);
}

function method2($arg){
	return $arg === 0;
}
$arg = 0;

$bench = new Bench(1000);
$bench->addProcess("method1", "method1", $arg);
$bench->addProcess("method2", "method2", $arg);
$bench->run();
echo $bench->html();
