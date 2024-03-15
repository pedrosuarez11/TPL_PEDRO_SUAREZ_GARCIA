<?php
require_once "./views/view.php";

class DirectoresView extends View
{

    public function agregarForm($error = "", $loggeado = false)
    {
        require "./tpls/directores.form.agregar.phtml";
    }
    public function modificarForm($error = "", $director, $id, $loggeado = false)
    {
        require "./tpls/directores.form.modificar.phtml";
    }
    public function detalles($director = null, $loggeado = false)
    {
        require "./tpls/directores.detalle.phtml";
    }
    public function listar($directores = [], $loggeado = false)
    {
        require "./tpls/directores.listar.phtml";
    }
}
