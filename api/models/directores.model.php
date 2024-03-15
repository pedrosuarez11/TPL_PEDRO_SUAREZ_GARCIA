<?php
require_once "./models/model.php";

class DirectoresModel extends Model
{
    private $campos = ["id", "nombre", "edad"];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($page = false, $orderBy = "id", $order = "ASC", $filterBy = "1", $filter = "1")
    {
        $limitStr = " ";
        if ($page) {
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

        $query = $this->db->prepare("SELECT * from directores WHERE $filterBy = ? ORDER BY $orderBy $order $limitStr");
        $query->execute([$filter]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByID($id)
    {
        $query = $this->db->prepare("SELECT * from directores WHERE id = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function create($data)
    {
        $query = $this->db->prepare("INSERT INTO directores (nombre, edad) values (? , ?)");
        return $query->execute([$data->nombre, $data->edad]);
    }

    public function updateByID($data, $id)
    {
        $query = $this->db->prepare("UPDATE directores set nombre= ?, edad = ? WHERE id = ?");
        return $query->execute([$data->nombre, $data->edad, $id]);
    }
}
