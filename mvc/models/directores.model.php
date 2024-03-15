<?php
require_once "./models/model.php";

class DirectoresModel extends Model
{

    public function __construct()
    {
        parent::__construct("directores");
    }

    public function putByID($id, $nombre, $edad)
    {
        $query = $this->db->prepare("UPDATE $this->table SET nombre = ? , edad = ?  WHERE id = ?");
        return $query->execute([$nombre, $edad, $id]);
    }

    public function insert($nombre, $edad)
    {
        $query = $this->db->prepare("INSERT INTO $this->table (nombre, edad) values (? , ?)");
        return $query->execute([$nombre, $edad]);
    }
}
