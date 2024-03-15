<?php

require_once "./controllers/controller.php";
require_once "./models/peliculas.model.php";
require_once "./models/directores.model.php";
require_once "./views/peliculas.view.php";

class PeliculasController extends Controller
{
    private $directoresModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = new PeliculasModel();
        $this->directoresModel = new DirectoresModel();
        $this->view = new PeliculasView();
    }

    public function showPostForm()
    {
        AuthHelper::verify();
        $directores = $this->directoresModel->getAll();
        $this->view->agregarForm(null, $directores, $this->loggeado);
    }

    public function showPutForm($params = [])
    {
        AuthHelper::verify();
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
            return;
        }
        $id = $params[":id"];
        $pelicula = $this->model->getByID($id);
        if (!$pelicula) {
            $this->view->error("No existe la pelicula con el id : $id", $this->loggeado);
            return;
        }
        $directores = $this->directoresModel->getAll();
        $this->view->modificarForm(null, $pelicula, $directores, $this->loggeado);
    }

    public function getByID($params = [])
    {
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
            return;
        }

        $id = $params[":id"];

        $pelicula = $this->model->getByID($id);

        if (!$pelicula) {
            $this->view->error("No existe la pelicula", $this->loggeado);
            return;
        }

        $director = $this->directoresModel->getByID($pelicula->director);

        $pelicula->director = $director->nombre;
        $this->view->detalles($pelicula, $this->loggeado);
    }

    public function getAll($params = [])
    {
        if (isset($_GET["director"]) && $_GET["director"] != "") {
            $director = $_GET["director"];
            $peliculas = $this->model->getAllByDirector($director);
        } else {
            $peliculas = $this->model->getAll();
        }

        if ($peliculas === false) {
            $this->view->error("Ocurrió un error inesperado", $this->loggeado);
            return;
        }

        foreach ($peliculas as $p) {
            $director = $this->directoresModel->getByID($p->director);
            $p->director = $director->nombre;
        }

        $directores = $this->directoresModel->getAll();

        $this->view->listar($peliculas, $directores, $this->loggeado);
    }

    public function deleteByID($params = [])
    {

        AuthHelper::verify();
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
            return;
        }

        $id = $params[":id"];

        $pelicula = $this->model->getByID($id);

        if (!$pelicula) {
            $this->view->error("No existe la pelicula", $this->loggeado);
            return;
        }

        $deleted = $this->model->deleteByID($id);
        if (!$deleted) {
            $this->view->error("No se pudo eliminar por un error inesperado", $this->loggeado);
            return;
        }

        header("Location: " . BASE_URL . "peliculas");
    }

    public function putByID($params = [])
    {

        AuthHelper::verify();
        if (!isset($params[":id"]) || $params[":id"] == "") {
            $this->view->error("Es necesario un id", $this->loggeado);
            return;
        }
        $id = $params[":id"];
        if (!isset($_POST["nombre"]) || !isset($_POST["director"]) || !isset($_POST["duracion"]) || !isset($_POST["fecha_estreno"]) || !isset($_POST["presupuesto"])) {
            $this->view->error("Es necesario completar los datos", $this->loggeado);
            return;
        }

        $pelicula = $this->model->getByID($id);

        if (!$pelicula) {
            $this->view->error("No existe la pelicula", $this->loggeado);
            return;
        }

        $nombre = $_POST["nombre"];
        $director = $_POST["director"];
        $duracion = $_POST["duracion"];
        $fecha_estreno = $_POST["fecha_estreno"];
        $presupuesto = $_POST["presupuesto"];


        $filePath = false;
        if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
            $filePath = "./src/imgs/" . uniqid("", true) . "." . strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $actualizada = $this->model->putByID_img($id, $nombre, $director, $duracion, $fecha_estreno, $presupuesto, $filePath);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath);
        } else {
            $actualizada = $this->model->putByID($id, $nombre, $director, $duracion, $fecha_estreno, $presupuesto);
        }
        if (!$actualizada) {
            $this->view->error("No se pudo actualizar por un error inesperado", $this->loggeado);
            return;
        }

        header("Location: " . BASE_URL . "peliculas/" . $id);
    }

    public function post()
    {
        AuthHelper::verify();
        if (!isset($_POST["nombre"]) || !isset($_POST["director"]) || !isset($_POST["duracion"]) || !isset($_POST["fecha_estreno"]) || !isset($_POST["presupuesto"])) {
            $this->view->error("Es necesario completar los datos", $this->loggeado);
            return;
        }

        $nombre = $_POST["nombre"];
        $director = $_POST["director"];
        $duracion = $_POST["duracion"];
        $fecha_estreno = $_POST["fecha_estreno"];
        $presupuesto = $_POST["presupuesto"];
        $filePath = "";
        if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
            $filePath = "./src/imgs/" . uniqid("", true) . "." . strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath);
        }

        $this->model->insert($nombre, $director, $duracion, $fecha_estreno, $presupuesto, $filePath);

        header("Location: " . BASE_URL . "peliculas");
    }
}
