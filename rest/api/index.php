<?php

include "model/user.class.php";
include "controller/userController.class.php";
include "src/router.class.php";
include "src/routerexception.class.php";

//var_dump($_SERVER);

$router = new Router("conf/routing.json");
$result = $router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_POST);
die(json_encode($result));
