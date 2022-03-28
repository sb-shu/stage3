<?php
    require_once "DatabaseInterface.php";
    require_once "Session.php";

    StartSession();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $verified = UserComparePassword($username, $password);
        if (!$verified) {
            // TODO: handle properly
            echo "Invalid username or password";
            die;
        } else {
            $user = GetUser($username);
            $_SESSION["username"] = $username;
            $_SESSION["user"] = $user;

            header("Location: index.php");
            exit;
        }
    }