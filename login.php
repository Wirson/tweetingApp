<?php
session_start();
require 'src/connection.php';
require 'src/User.php';
//do no let logged user log in again
if (isset($_SESSION['userId'])) {
    header("Location: index.php");
}
//check user, head to main page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = User::loadUserByEmail($conn, $_POST['mail']);
    if ($user != false) {
        if ($user->passVerify($_POST['pass'])) {
            $_SESSION ['userId'] = $user->getId();
            $_SESSION ['email'] = $user->getEmail();
            $_SESSION ['userName'] = $user->getUsername();
            header("Location: index.php");
        } else {
            echo 'Wrong e-mail or password!';
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
