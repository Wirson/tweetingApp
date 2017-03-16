<?php
session_start();
require 'src/connection.php';
require 'src/User.php';
require 'src/Tweet.php';
require 'src/Comment.php';
var_dump($_SESSION);
//pobieranie id posta
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tweetId = $_GET['id'];
    var_dump($tweetId);
}
//getting tweet
$tweet = Tweet::loadTweetById($conn, $tweetId);
var_dump($tweet);

//deleting tweet
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
        <div>
            <a href="showUser.php"><button>Back to your Profile</button></a>
        </div>        
        <hr>
        <div>
            <?php
            echo 'Tweet id: ' . $tweet->getId() . '<br>';
            echo 'created by : ' . $_SESSION['userName'] . ' on ' . $tweet->getCreationDate() . '<br>';
            echo $tweet->getText() . '<br>';
            ?>
            <form action="" method="POST">
                <input type="submit" name="delete" value="Delete?"/>
            </form>
            <h3>Comments</h3>
            <br>
            <?php
            //pobieranie komentarza do posta
            $comments = Comment::loadAllCommentsByTweetId($conn, $tweet->getId());
            if ($comments) {
                foreach ($comments as $value) {
                    //loading author's name
                    $commAuthor = User::loadUserById($conn, $value->getUserId());
                    echo 'comment from ' . $commAuthor->getUsername() . '<br>' .
                    $value->getText() . '<br>' . 'created on: ' . $value->getCreationDate() . '<br><br>';
                }
            } else {
                echo 'no comments!';
            }
            ?>
        </div>

    </body>
</html>
