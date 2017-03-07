<?php
session_start();
require 'connection.php';
require 'User.php';
var_dump($_SESSION);
if (isset($_SESSION['userId'])) {
    echo 'Jesteś zalogowany!' . '<br>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    //walidacja
    
    $user->setUsername($_POST['user']);
    $user->setEmail($_POST['mail']);
    $user->setPass($_POST['pass']);
    if ($user->saveToDB($conn)) {
        echo 'User registered';
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
