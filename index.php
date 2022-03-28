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

    $users = GetRandomPublicAccounts(5);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <div id="session-bar">
            <?php if (IsLoggedIn()): ?>
                You are logged in as <?=$_SESSION["username"];?>. <a href="profileView.php">Go to your profile</a> or <a href="logout.php">log out</a>.
            <?php else: ?>
                <form action="" method="POST">
                    <b>Log in:</b>
                    <input type="text" name="username" id="username" placeholder="Username" required />
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    <button type="submit">Log in</button>
                    or <a href="register.php">Register</a>
                </form>
            <?php endif; ?>
        </div>
        <div id="home-profiles">
            <div class="title">Public Profiles</div>
            <?php foreach ($users as $username => $user): ?>
                <div class="item">
                    <a href="profileView.php?user=<?=$username;?>" class="link"></a>
                    <div class="centre">
                        <img src="Donny.webp" />
                        <div class="name"><?=$user->FirstName;?> <?=$user->LastName;?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>