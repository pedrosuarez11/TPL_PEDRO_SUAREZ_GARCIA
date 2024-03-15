<?php
require_once "./helpers/auth.helper.php";

abstract class Controller
{
    protected $model;
    protected $view;
    protected $loggeado;

    public function __construct() {
        $this->loggeado = AuthHelper::isLoggedIn();
    }
}
