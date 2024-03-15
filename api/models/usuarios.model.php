<?php
require_once "./models/model.php";

class UsuariosModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getByUsername($username = "")
    {
        $query = $this->db->prepare("SELECT * from usuarios WHERE username = ?");
        $query->execute([$username]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function insert($username, $hash)
    {
        $query = $this->db->prepare("INSERT INTO usuarios (username, password) values (? , ?)");
        return $query->execute([$username, $hash]);
    }
}
