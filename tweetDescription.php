<?php
session_start();
require 'connection.php';
require 'Tweet.php';
var_dump($_SESSION);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tweetId = $_GET['id'];
}

$tweet = Tweet::loadTweetById($conn, $tweetId);
var_dump($tweet);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $tweet->deleteTweet($conn);
    echo '<a href="showUser.php"><button>Your Profile</button></a>';
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
        <?php
            echo 'Tweet id: ' . $tweet->getId() . '<br>';
            echo 'created by : ' . $_SESSION['userName'] . ' on ' . $tweet->getCreationDate() . '<br>';
            echo $tweet->getText() . '<br>';
        ?>
        <form action="" method="POST">
        <input type="submit" name="delete" value="Delete?"/>
        </form>
    </body>
</html>
