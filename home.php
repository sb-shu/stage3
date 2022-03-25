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

            header("Location: home.php");
            exit;
        }
    }
?>

<html>
    <head>
    </head>
    <body>
        <?php if (IsLoggedIn()): ?>
            You are logged in as <?=$_SESSION["username"];?>. <a href="logout.php">Log out</a>
        <?php else: ?>
            <form action="" method="POST">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required />
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required />
                <button type="submit">Log in</button>
                <a href="register.php">Register</a>
            </form>
        <?php endif; ?>
    </body>
</html>