<?php
session_start();
require 'connection.php';
require 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = User::loadUserByEmail($conn, $_POST['mail']);
    if ($user != false) {
        if ($user->passVerify($_POST['pass'])) {
            $_SESSION ['userId'] = $user->getId();
            $_SESSION ['email'] = $user->getEmail();
            $_SESSION ['userName'] = $user->getUsername();
        }
    }
}
var_dump($_SESSION);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div>
            <a href="index.php"><button>Back to main page</button></a>
            <h1>paraTwitter</h1>
            <h2>Log In</h2>
            <form action="" method="POST">
                <label for="mail">E-mail</label>
                <input type="text" name="mail"/>
                <br>
                <label for="pass">Password</label>
                <input type="password" name="pass"/>
                <br>            
                <input type="submit" value="Log In"/>
            </form>
        </div>
    </body>
</html>
