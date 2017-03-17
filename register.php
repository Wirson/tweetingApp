<?php
session_start();
require 'src/connection.php';
require 'src/User.php';
var_dump($_SESSION);
//do no let logged user log in again
if (isset($_SESSION['userId'])) {
    header("Location: index.php");
}
//create new user, save to DB, head to login page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user']) && isset($_POST['mail']) && isset($_POST['pass'])) {
        //sanitize and validate inputs
        $username = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
        $mail = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($mail);
        $user->setPass($pass);
        //checking if email already registered - check User.php methods
        if ($user->getId() !== -1) {                
                header("Location: login.php");
            } else {
                echo "E-mail already used!";
            }
    } else {
        echo 'fill all fields!';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>paraTwitter</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="login.php"><button>Log In</button></a>        
        <a href="index.php"><button>Strona główna</button></a>
        <div>
            <h1>paraTwitter</h1>
            <h2>Register</h2>
            <form action="" method="POST">
                <label for="user">Username</label>
                <input type="text" name="user" id="user"/>
                <br>
                <label for="pass">Password</label>
                <input type="password" name="pass" id="pass"/>
                <br>
                <label for="mail">E-mail</label>
                <input type="text" name="mail" id="mail"/>
                <br>
                <input type="submit" value="Register"/>
            </form>
        </div>
    </body>
</html>
