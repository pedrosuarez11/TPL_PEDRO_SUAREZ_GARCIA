<?php

require_once "./helpers/auth.api.helper.php";
require_once "./models/directores.model.php";
require_once "./views/api.view.php";

class DirectoresApiController
{

    private $model;
    private $view;

    public function __construct()
    {
        $this->model = new DirectoresModel();
        $this->view = new ApiView();
    }

    public function getData()
    {
        $data = json_decode(file_get_contents("php://input"));
        return $data;
    }

    public function post()
    {
        // verifico token
        if (!AuthApiHelper::verify()) {
            $this->view->response("Unauthorized", 401);
            return;
        };
        $data = $this->getData();
        if (!isset($data->nombre) || !isset($data->edad)) {
            // error
            $this->view->response("Es necesario completar los datos obligatorios", 400);
            return;
        }

        $created = $this->model->create($data);

        if (!$created) {
            $this->view->response("Ocurrio un error al intentar insertar el director", 500);
            return;
        }

        $this->view->response("Insertada con exito", 201);
    }

    public function putByID($params = [])
    {
        // verifico token
        /* if(!AuthApiHelper::verify()){
            $this->view->response("Unauthorized", 401);
            return;
        }; */

        if (empty($params) || !isset($params[":id"])) {
            $this->view->response("Es necesario proveer un id de director", 400);
            return;
        }

        $id = $params[":id"];

        $director =  $this->model->getByID($id);

        if (!$director) {
            $this->view->response("No existe el director.", 404);
            return;
        }

        $data = $this->getData();
        if (!isset($data->nombre) || !isset($data->edad)) {
            // error
            $this->view->response("Es necesario completar los datos obligatorios", 400);
            return;
        }

        $updated = $this->model->updateByID($data, $id);

        if (!$updated) {
            $this->view->response("Ocurrio un error al intentar actualizar el director", 500);
            return;
        }

        $this->view->response("Actualizado con exito", 200);
    }

    public function getAll($params = [])
    {

        $order = "ASC";
        $orderBy = "id";
        $page = false;

        if (isset($_GET["order"]) && ($_GET["order"] == "asc" || $_GET["order"] == "ASC" || $_GET["order"] == "desc" || $_GET["order"] == "DESC")) {
            $order = $_GET["order"];
        }
        if (isset($_GET["page"]) && intval($_GET["page"]) > 0) {
            $page = $_GET["page"];
        }
        if (isset($_GET["orderBy"]) && $_GET["orderBy"] != "") {
            $orderBy = $_GET["orderBy"];
        }

        if (isset($_GET["filterBy"]) && isset($_GET["filter"])) {
            $filterBy = $_GET["filterBy"];
            $filter = $_GET["filter"];
            $directores = $this->model->getAll($page, $orderBy, $order, $filterBy, $filter);
        } else {
            $directores = $this->model->getAll($page, $orderBy, $order);
        }

        if ($directores === false) {
            $this->view->response("Ocurrio un error inesperado, intente nuevamente", 500);
            return;
        }


        $this->view->response($directores, 200);
    }

    public function getByID($params = [])
    {

        if (empty($params) || !isset($params[":id"])) {
            $this->view->response("Es necesario proveer un id de director", 400);
            return;
        }

        $id = $params[":id"];

        $director =  $this->model->getByID($id);

        if (!$director) {
            $this->view->response("No existe el director.", 404);
            return;
        }


        $this->view->response($director, 200);
    }
}
