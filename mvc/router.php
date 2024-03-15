<?php
require_once "./controllers/usuarios.controller.php";
require_once "./controllers/peliculas.controller.php";
require_once "./controllers/directores.controller.php";
require_once './libs/Router.php';
require_once "./config/config.php";
    
$router = new Router();

$router->addRoute("/home", "GET", "UsuariosController", "showHome");

$router->addRoute("/registro", "GET", "UsuariosController", "showRegistro");
$router->addRoute("/registro", "POST", "UsuariosController", "registrar");

$router->addRoute("/login", "GET", "UsuariosController", "showLogin");
$router->addRoute("/login", "POST", "UsuariosController", "login");

$router->addRoute("/logout", "GET", "UsuariosController", "logout");

$router->addRoute("/peliculas/agregar", "GET", "PeliculasController", "showPostForm");
$router->addRoute("/peliculas/putForm/:id", "GET", "PeliculasController", "showPutForm");
$router->addRoute("/peliculas/modificar/:id", "POST", "PeliculasController", "putByID");
$router->addRoute("/peliculas/eliminar/:id", "GET", "PeliculasController", "deleteByID");
$router->addRoute("/peliculas", "GET", "PeliculasController", "getAll");
$router->addRoute("/peliculas", "POST", "PeliculasController", "post");
$router->addRoute("/peliculas/:id", "GET", "PeliculasController", "getByID");

$router->addRoute("/directores/agregar", "GET", "DirectoresController", "showPostForm");
$router->addRoute("/directores/putForm/:id", "GET", "DirectoresController", "showPutForm");
$router->addRoute("/directores/modificar/:id", "POST", "DirectoresController", "putByID");
$router->addRoute("/directores/eliminar/:id", "GET", "DirectoresController", "deleteByID");
$router->addRoute("/directores", "GET", "DirectoresController", "getAll");
$router->addRoute("/directores", "POST", "DirectoresController", "post");
$router->addRoute("/directores/:id", "GET", "DirectoresController", "getByID");

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);

?>