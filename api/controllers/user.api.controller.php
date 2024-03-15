<?php 

require_once "./helpers/auth.api.helper.php";
require_once "./models/usuarios.model.php";
require_once "./views/api.view.php";

class UserApiController{

    private $model;
    private $view;

    public function __construct()
    {
        $this->model = new UsuariosModel();
        $this->view = new ApiView();
        
    }

    public function getToken(){

        // headers de authorization
        $auth = AuthApiHelper::getAuthHeaders();
        if(empty($auth)){
            $this->view->response("Los datos de autorizacion estan incompletos.", 401);
            return;
        }

        //"Authorization: (basic user:pass)";
        $auth = explode(" ", $auth); // ["Basic", "user:pass"]

        // Basic
        if ($auth[0] != "Basic"){
            $this->view->response("El formato de autorizacion es incorrecto. (Debe ser Basic)", 401);
            return;
        };

        // recupero user:pass
        $userpass = base64_decode($auth[1]);
        $userpass = explode(":", $userpass);// ["user", "pass"]

        // username
        // password
        $username = $userpass[0];
        $password = $userpass[1];

        // verifico el usuario
        $usuario = $this->model->getByUsername($username);

        // verificar que exista el usuario con el username
        if (!$usuario) {
            $this->view->response("El usuario es incorrecto, intente nuevamente.", 401);
            return;
        }

        // verifico la contraseña 

        if (!password_verify($password, $usuario->password)) {
            $this->view->response("La contraseña es incorrecta, intente nuevamente.", 401);
            return;
        }

        // crear $payload = ["user" => username, "admin" => true];
        $payload = ["username" => $username, "admin" => true];

        $token = AuthApiHelper::createToken($payload);

        $data = new stdClass();
        $data->token = $token;
        
        $this->view->response($data, 200);
    }

}