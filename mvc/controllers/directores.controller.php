<?php

require_once "./controllers/controller.php";
require_once "./models/directores.model.php";
require_once "./views/directores.view.php";

class DirectoresController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new DirectoresModel();
        $this->view = new DirectoresView();
    }

    public function showPostForm()
    {
        AuthHelper::verify();
        $this->view->agregarForm(null, $this->loggeado);
    }

    public function showPutForm($params = [])
    {
        AuthHelper::verify();
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
            return;
        }
        $id = $params[":id"];

        $director = $this->model->getByID($id);

        if (!$director) {
            $this->view->error("No existe el director con el id : $id", $this->loggeado);
            return;
        }
        $this->view->modificarForm(null, $director, $this->loggeado);
    }

    public function getByID($params = [])
    {
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
        }

        $id = $params[":id"];

        $director = $this->model->getByID($id);

        if (!$director) {
            $this->view->error("No existe el director", $this->loggeado);
        }

        $this->view->detalles($director, $this->loggeado);
    }

    public function getAll($params = [])
    {

        $directores = $this->model->getAll();

        if ($directores === false) {
            $this->view->error("Ocurrió un error inesperado", $this->loggeado);
        }

        $this->view->listar($directores, $this->loggeado);
    }

    public function deleteByID($params = [])
    {

        AuthHelper::verify();
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
            return;
        }

        $id = $params[":id"];

        $director = $this->model->getByID($id);

        if (!$director) {
            $this->view->error("No existe el director", $this->loggeado);
            return;
        }

        $deleted = $this->model->deleteByID($id);
        if (!$deleted) {
            $this->view->error("No se pudo eliminar por un error inesperado", $this->loggeado);
            return;
        }

        header("Location: " . BASE_URL . "directores");
    }
    public function putByID($params = [])
    {

        AuthHelper::verify();
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
            return;
        }

        $id = $params[":id"];

        if (!isset($_POST["nombre"]) || !isset($_POST["edad"])) {
            $this->view->error("Es necesario completar los datos", $this->loggeado);
            return;
        }

        $nombre = $_POST["nombre"];
        $edad = $_POST["edad"];

        $actualizado = $this->model->putByID($id, $nombre, $edad);

        if (!$actualizado) {
            $this->view->error("No se pudo actualizar por un error inesperado", $this->loggeado);
            return;
        }

        header("Location: " . BASE_URL . "directores/" . $id);
    }

    public function post()
    {
        AuthHelper::verify();
        if (!isset($_POST["nombre"]) || !isset($_POST["edad"])) {
            $this->view->error("Es necesario completar los datos", $this->loggeado);
            return;
        }

        $nombre = $_POST["nombre"];
        $edad = $_POST["edad"];

        $creado = $this->model->insert($nombre, $edad);

        if (!$creado) {
            $this->view->error("No se pudo insertar por un error inesperado", $this->loggeado);
            return;
        }

        header("Location: " . BASE_URL . "directores");
    }
}
