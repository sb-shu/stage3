<?php
    require_once "DatabaseInterface.php";

    $sessionUser = null;

    function StartSession() {
        global $sessionUser;
        session_start();
        if (isset($_SESSION["username"])) {
            $sessionUser = GetUser($_SESSION["username"]);
        }
    }

    function LogOutSession() {
        session_destroy();
    }

    function GetSessionUser() {
        global $sessionUser;
        return $sessionUser;
    }

    function IsLoggedIn() {
        global $sessionUser;
        return $sessionUser != null;
    }