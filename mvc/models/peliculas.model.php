<?php
require_once "./models/model.php";

class PeliculasModel extends Model
{

    public function __construct()
    {
        parent::__construct("peliculas");
    }


    public function putByID($id, $nombre, $director, $duracion, $fecha_estreno, $presupuesto)
    {
        $query = $this->db->prepare("UPDATE $this->table SET nombre = ? , director = ? , duracion = ? , fecha_estreno = ? , presupuesto = ?  WHERE id = ?");
        return $query->execute([$nombre, $director, $duracion, $fecha_estreno, $presupuesto, $id]);
    }

    public function putByID_img($id, $nombre, $director, $duracion, $fecha_estreno, $presupuesto, $img)
    {
        $query = $this->db->prepare("UPDATE $this->table SET nombre = ? , director = ? , duracion = ? , fecha_estreno = ? , presupuesto = ? , img = ? WHERE id = ?");
        return $query->execute([$nombre, $director, $duracion, $fecha_estreno, $presupuesto, $img, $id]);
    }
    
    public function getAllByDirector($director)
    {
        $query = $this->db->prepare("SELECT * from $this->table");
        $query->execute([$director]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function insert($nombre, $director, $duracion, $fecha_estreno, $presupuesto, $img = "")
    {
        $query = $this->db->prepare("INSERT INTO $this->table (nombre, director, duracion, fecha_estreno, presupuesto, img) values (? , ?, ?, ?, ?, ?)");
        return $query->execute([$nombre, $director, $duracion, $fecha_estreno, $presupuesto, $img]);
    }
}
