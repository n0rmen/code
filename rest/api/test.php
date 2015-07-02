<?php
include "src/api.inc.php";

$api = new Rest;
$result = $api->get("user/1");
// $result = $api->post("user", array('name' => "James Bond"));
// $result = $api->put("user/1", array('name' => "James Bond"));
// $result = $api->delete("user/1");
var_dump($result);
