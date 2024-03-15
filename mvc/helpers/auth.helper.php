<?php

class AuthHelper
{

    private static function startSession(){
        if (!isset($_SESSION["username"])) {
            session_start();
        }
    }

    public static function loginUser($usuario)
    {
        AuthHelper::startSession();
        $_SESSION["username"] = $usuario->username;
    }

    public static function checkLoggedRedirect()
    {
        AuthHelper::startSession();
        if (isset($_SESSION["username"])) {
            header("Location: " . BASE_URL . "home");
            die;
        }
    }

    public static function isLoggedIn(){

        AuthHelper::startSession();
        return isset($_SESSION["username"]);
            
    }
    public static function verify()
    {
        AuthHelper::startSession();
        if (!isset($_SESSION["username"])) {
            header("Location: " . BASE_URL . "login");
            die;
        }
    }

    public static function logout()
    {
        AuthHelper::startSession();
        session_destroy();
        header("Location: " . BASE_URL . "home");
        die;
    }
}
