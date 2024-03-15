<?php
require_once "./models/model.php";

class PeliculasModel extends Model
{
    private $campos = ["id", "nombre", "director", "duracion", "fecha_estreno", "presupuesto"];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($page = false, $orderBy = "id", $order = "ASC", $filterBy = "1", $filter = "1")
    {
        $limitStr = " ";
        if($page){
            $limit = 2;
            $limitStr = "LIMIT $limit OFFSET " . ($limit * $page) - $limit . " ";
        }

        if (!in_array($filterBy, $this->campos)) {
            $filterBy = "1";
        }

        if (!in_array($orderBy, $this->campos)) {
            $orderBy = "id";
        }

        $order = strtoupper($order);
        $order = $order === "ASC" || $order === "DESC" ? $order : "ASC";

        $query = $this->db->prepare("SELECT * from peliculas WHERE $filterBy = ? ORDER BY $orderBy $order $limitStr");
        $query->execute([$filter]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByID($id)
    {
        $query = $this->db->prepare("SELECT * from peliculas WHERE id = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function create($data)
    {
        $query = $this->db->prepare("INSERT INTO peliculas (nombre, director, duracion, fecha_estreno, presupuesto) values (? , ?, ? , ?, ?)");
        return $query->execute([$data->nombre, $data->director, $data->duracion, $data->fecha_estreno, $data->presupuesto]);
    }

    public function updateByID($data, $id)
    {
        $query = $this->db->prepare("UPDATE peliculas set nombre= ?, director = ?, duracion = ?, fecha_estreno = ?, presupuesto = ? WHERE id = ?");
        return $query->execute([$data->nombre, $data->director, $data->duracion, $data->fecha_estreno, $data->presupuesto, $id]);
    }
}
