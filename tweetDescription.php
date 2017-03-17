<?php
session_start();
require 'src/connection.php';
require 'src/User.php';
require 'src/Tweet.php';
require 'src/Comment.php';

//deleting tweet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $tweet = Tweet::loadTweetById($conn, $_GET['id']);
    $tweet->deleteTweet($conn);
    header('Location: showUser.php');
}

//loading post id from GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tweetId = $_GET['id'];
}

//loading tweet
$tweet = Tweet::loadTweetById($conn, $tweetId);

//check wheter current user is an author of that tweet, due to get method
if ($tweet->getUserId() != $_SESSION['userId']) {
    header('Location: showUser.php');
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
