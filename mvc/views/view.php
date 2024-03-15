<?php

abstract class View
{

    public function home($error = "", $loggeado = false)
    {
        require "./tpls/home.phtml";
    }

    public function error($error = "")
    {
        require "./tpls/mostrar.error.phtml";
    }
    public abstract function detalles($data = null);

    public abstract function listar($data = []);
}
