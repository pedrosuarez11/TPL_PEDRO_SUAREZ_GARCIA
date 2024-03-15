<?php
require_once "./views/view.php";

class UsuariosView extends View
{

    public function formRegistro($error = "", $loggeado = false)
    {
        require "./tpls/form.registro.phtml";
    }
    public function formLogin($error = "", $loggeado = false)
    {
        require "./tpls/form.login.phtml";
    }
    public function detalles($usuario = null, $loggeado = false)
    {
        require "./tpls/usuario.detalle.phtml";
    }

    public function listar($usuarios = [], $loggeado = false)
    {
        require "./tpls/usuarios.listar.phtml";
    }
}
