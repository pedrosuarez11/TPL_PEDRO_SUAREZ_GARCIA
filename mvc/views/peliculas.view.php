<?php
require_once "./views/view.php";

class PeliculasView extends View
{
    public function agregarForm($error = "", $directores = [], $loggeado = false)
    {
        require "./tpls/peliculas.form.agregar.phtml";
    }
    public function modificarForm($error = "", $pelicula, $directores = [], $loggeado = false)
    {
        require "./tpls/peliculas.form.modificar.phtml";
    }
    public function detalles($pelicula = null, $loggeado = false)
    {
        require "./tpls/peliculas.detalle.phtml";
    }

    public function listar($peliculas = [], $directores = [], $loggeado = false)
    {
        require "./tpls/peliculas.listar.phtml";
    }
}
