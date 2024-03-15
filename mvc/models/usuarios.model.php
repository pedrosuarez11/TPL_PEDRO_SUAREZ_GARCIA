<?php
require_once "./models/model.php";

class UsuariosModel extends Model
{

    public function __construct()
    {
        parent::__construct("usuarios");
    }

    public function getByUsername($username = "")
    {
        $query = $this->db->prepare("SELECT * from $this->table WHERE username = ?");
        $query->execute([$username]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function insert($data)
    {
        $username = $data["username"];
        $hash = $data["hash"];
        $query = $this->db->prepare("INSERT INTO $this->table (username, password) values (? , ?)");
        return $query->execute([$username, $hash]);
    }
}
