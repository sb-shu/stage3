<?php
    require_once "Session.php";

    StartSession();
    LogOutSession();
    header("Location: index.php");