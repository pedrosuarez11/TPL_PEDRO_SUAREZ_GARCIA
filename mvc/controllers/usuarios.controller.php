<?php

require_once "./controllers/controller.php";
require_once "./models/usuarios.model.php";
require_once "./views/usuarios.view.php";

class UsuariosController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new UsuariosModel();
        $this->view = new UsuariosView();
    }

    public function registrar()
    {
        // pregunto si tengo todos datos necesarios
        if (!isset($_POST["username"]) || !isset($_POST["password"])) {
            $this->view->formRegistro("Uno o mas datos faltantes, intente nuevamente.", $this->loggeado);
            return;
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        $usuario = $this->model->getByUsername($username);

        // verificar que no exista otro username igual
        if ($usuario) {
            $this->view->formRegistro("El usuario ya existe, intente nuevamente.", $this->loggeado);
            return;
        }

        // password hash
        $password_hasheada = password_hash($password, PASSWORD_BCRYPT);

        // insertar nuevo usuario en base de datos

        $exito = $this->model->insert($username, $password_hasheada);

        if (!$exito) {
            $this->view->formRegistro("Se ha producido un error inesperado, intente nuevamente.", $this->loggeado);
            return;
        }

        header("Location: " . BASE_URL . "login");
    }

    public function showHome()
    {
        AuthHelper::verify();

        $this->view->home("",  $this->loggeado);
    }
    
    public function showRegistro()
    {
        $this->view->formRegistro("", $this->loggeado);
    }

    public function showLogin()
    {
        $this->view->formLogin("", $this->loggeado);
    }

    public function login()
    {
        // pregunto si tengo todos datos necesarios
        if (!isset($_POST["username"]) || !isset($_POST["password"])) {
            $this->view->formLogin("Uno o mas datos faltantes, intente nuevamente.", $this->loggeado);
            return;
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        $usuario = $this->model->getByUsername($username);

        // verificar que exista el usuario con el username
        if (!$usuario) {
            $this->view->formLogin("El usuario no existe, intente nuevamente.", $this->loggeado);
            return;
        }

        // verifico la contraseña 

        if (!password_verify($password, $usuario->password)) {
            $this->view->formLogin("La contraseña es errónea, intente nuevamente.", $this->loggeado);
            return;
        }

        AuthHelper::loginUser($usuario);

        header("Location: " . BASE_URL . "home");
    }

    public function logout()
    {
        AuthHelper::verify();
        AuthHelper::logout();
    }
}
