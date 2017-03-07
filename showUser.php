<?php
session_start();
require 'connection.php';
require 'User.php';
require 'Tweet.php';

//checking log in state
if (isset($_SESSION['userId'])) {
    $loggedUser = User::loadUserById($conn, $_SESSION['userId']);
} else {
    exit('No logged user!');
}

//primal logging out functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    session_destroy();
    exit('Logged Out!');
}
//primal deleting user functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $loggedUser->delete($conn);
    session_destroy();
    exit('User deleted!'); //add deleting from db
}

//conditional for updating DB
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['pass'])) {
    $loggedUser->setUsername($_POST['user']);
    $loggedUser->setEmail($_POST['mail']);
    $loggedUser->setPass($_POST['pass']);
    if ($loggedUser->saveToDB($conn)) {
        echo "Data updated!";
    } else {
        echo "Change data and password";
    }
}

//showing all user's posts
$tweets = Tweet::loadAllTweetsByUserId($conn, $loggedUser->getId());
var_dump($loggedUser);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>paraTwitter</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="index.php"><button>Go to main page!</button></a>
        <form action="" method="POST">
            <input type="submit" name="logout" value="LogOut"/>
        </form>
        <h1>Update your data!</h1>
        <p>Your ID: <?php echo $loggedUser->getId() ?></p>
        <form action="" method="POST">            
            <label for="user">Username</label>
            <input type="text" name="user" value="<?php echo $loggedUser->getUsername(); ?>"/>
            <br>            
            <label for="mail">E-mail</label>
            <input type="text" name="mail" value="<?php echo $loggedUser->getEmail(); ?>"/>
            <br>
            <label for="pass">Password</label>
            <input type="password" name="pass" placeholder="NEW PASSWORD"/>
            <br>
            <input type="submit" value="Update"/>
            <input type="submit" name="delete" value="Delete"/>
        </form>
        <hr>
        <h3>Your Tweets:</h3>
        <?php
        foreach ($tweets as $value) {
            $id = $value->getId();
            echo '<a href="tweetDescription.php?id=' . $id . '">' . 'Tweet # ';
            echo $id . '</a>' . '<br>' . $value->getText() . '<br>';
        }
        ?>
    </body>
</html>
