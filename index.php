<?php
session_start();

require 'connection.php';
require 'User.php';
require 'Tweet.php';

//primal logging out functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    session_destroy();
    exit('Logged Out!');
}

var_dump($user);
var_dump($_SESSION);
//getting ALL tweets
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['userId'])) {
    $tweet = new Tweet;
    
    $tweet->setUserId($_SESSION['userId']);
    $tweet->setText($_POST['text']);
    $tweet->setCreationDate(date('Y-m-d'));
    $tweet->saveToDB($conn);
}

$tweet = Tweet::loadAllTweets($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>paraTwitter</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
        if (isset($_SESSION['userId'])) {
            echo '<a href="showUser.php"><button>Your Profile</button></a>';
            echo '<form action="" method="POST"><input type="submit" name="logout" value="LogOut"/></form>';
        } else {
            echo "Please Log In<br>";
        echo '<a href="register.php"><button>Register</button></a>';
        echo '<a href="login.php"><button>Log in</button></a>';
        }
        ?>
        <form action="" method="POST">
            <textarea name="text"></textarea>
            <br>
            <input type="submit" value="Add Tweet"/>
        </form>
        <div>
            <?php 
            foreach ($tweet as $value) {
                echo 'Tweet # ' . $value->getId() . ' of user # ' 
                        . $value->getUserId() . '<br>' . $value->getText() . '<br>'
                        . 'written on ' . $value->getCreationDate() . '<br><br>';
            }
            ?>
        </div>
    </body>
</html>
