<?php
    require_once "DatabaseInterface.php";
    require_once "Session.php";

    StartSession();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $password = $_POST["password"];

        if (DoesUserExist($username)) {
            // TODO: handle properly
            echo "A user with that username already exists";
            die;
        }

        $user = CreateUser($username, $password);
        $user->FirstName = $firstName;
        $user->LastName = $lastName;
        $user->SaveChanges();
        
        $_SESSION["username"] = $username;
        $_SESSION["user"] = $user;

        header("Location: home.php");
        exit;
    }
?>

<html>
    <head>
    </head>
    <body>
        <?php if (IsLoggedIn()): ?>
            You are already logged in!
        <?php else: ?>
            <form action="" method="POST">
                <div>
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required />
                </div>
                <div>
                    <label for="firstName">First name</label>
                    <input type="text" name="firstName" id="firstName" required />
                </div>
                <div>
                    <label for="lastName">Last name</label>
                    <input type="text" name="lastName" id="lastName" required />
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required />
                </div>
                <div>
                    <button type="submit">Log in</button>
                </div>
            </form>
        <?php endif; ?>
    </body>
</html>