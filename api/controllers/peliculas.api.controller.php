<?php

require_once "./helpers/auth.api.helper.php";
require_once "./models/peliculas.model.php";
require_once "./views/api.view.php";

class PeliculaSApiController
{

    private $model;
    private $view;

    public function __construct()
    {
        $this->model = new PeliculasModel();
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
        if (!isset($data->nombre) || !isset($data->director) || !isset($data->duracion) || !isset($data->fecha_estreno) || !isset($data->presupuesto)) {
            // error
            $this->view->response("Es necesario completar los datos obligatorios", 400);
            return;
        }

        $created = $this->model->create($data);

        if (!$created) {
            $this->view->response("Ocurrio un error al intentar insertar la pelicula", 500);
            return;
        }

        $this->view->response("Insertada con exito", 201);
    }

    public function putByID($params = [])
    {
        // verifico token
        if(!AuthApiHelper::verify()){
            $this->view->response("Unauthorized", 401);
            return;
        };

        if (empty($params) || !isset($params[":id"])) {
            $this->view->response("Es necesario proveer un id de pelicula", 400);
            return;
        }

        $id = $params[":id"];

        $pelicula =  $this->model->getByID($id);

        if (!$pelicula) {
            $this->view->response("No existe la pelicula.", 404);
            return;
        }

        $data = $this->getData();
        if (!isset($data->nombre) || !isset($data->director) || !isset($data->duracion) || !isset($data->fecha_estreno) || !isset($data->presupuesto)) {
            // error
            $this->view->response("Es necesario completar los datos obligatorios", 400);
            return;
        }

        $updated = $this->model->updateByID($data, $id);

        if (!$updated) {
            $this->view->response("Ocurrio un error al intentar actualizar la pelicula", 500);
            return;
        }

        $this->view->response("Actualizada con exito", 200);
    }

    public function getAll($params = [])
    {
        //                      filtro                  ordenamiento                      sentido         paginacion  
        // GET /peliculas?    director=3  &  orderBy=nombre/director/fecha_estreno/... & order=desc   &     page=2

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
            $peliculas = $this->model->getAll($page, $orderBy, $order, $filterBy, $filter);
        } else {
            $peliculas = $this->model->getAll($page, $orderBy, $order);
        }

        if ($peliculas === false) {
            $this->view->response("Ocurrio un error inesperado, intente nuevamente", 500);
            return;
        }


        $this->view->response($peliculas, 200);
    }

    public function getByID($params = [])
    {

        if (empty($params) || !isset($params[":id"])) {
            $this->view->response("Es necesario proveer un id de pelicula", 400);
            return;
        }

        $id = $params[":id"];

        $pelicula =  $this->model->getByID($id);

        if (!$pelicula) {
            $this->view->response("No existe la pelicula.", 404);
            return;
        }


        $this->view->response($pelicula, 200);
    }
}
