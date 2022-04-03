<?php
    require_once "DatabaseInterface.php";
    require_once "Session.php";

    StartSession();

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
                <form action="login.php" method="POST">
                    <b>Log in:</b>
                    <input type="text" name="username" id="username" placeholder="Username" required />
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    <button type="submit">Log in</button>
                    or <a href="register.php">Register</a>
                </form>
            <?php endif; ?>
        </div>
        <div id="home-profiles">
            <div class="title">Public Portfolios</div>
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