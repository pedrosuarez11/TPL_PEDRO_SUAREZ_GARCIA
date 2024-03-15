<?php
require_once "./controllers/user.api.controller.php";
require_once "./controllers/peliculas.api.controller.php";
require_once "./controllers/directores.api.controller.php";
require_once './libs/Router.php';

$router = new Router();

$router->addRoute("user/token", "GET", "UserApiController", "getToken");
$router->addRoute("peliculas", "GET", "PeliculasApiController", "getAll");
$router->addRoute("peliculas/:id", "GET", "PeliculasApiController", "getByID");
$router->addRoute("peliculas", "POST", "PeliculasApiController", "post");
$router->addRoute("peliculas/:id", "PUT", "PeliculasApiController", "putByID");

$router->addRoute("directores", "GET", "DirectoresApiController", "getAll");
$router->addRoute("directores/:id", "GET", "DirectoresApiController", "getByID");
$router->addRoute("directores", "POST", "DirectoresApiController", "post");
$router->addRoute("directores/:id", "PUT", "DirectoresApiController", "putByID");

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);
?>