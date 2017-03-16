<?php
session_start();

require 'src/connection.php';
require 'src/User.php';
require 'src/Tweet.php';
require 'src/Comment.php';

//primitive logging out functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    session_destroy();
    exit('Logged Out!');
}

var_dump($user);
var_dump($_SESSION);
//creating new tweet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['userId'])) {
    if (isset($_POST['text'])) {
        $tweet = new Tweet;

        $tweet->setUserId($_SESSION['userId']);
        $tweet->setText($_POST['text']);
        $tweet->setCreationDate(date('Y-m-d'));
        $tweet->saveToDB($conn);
//creating new comment        
    } elseif (isset($_POST['comment'])) {
        $comm = new Comment;

        $comm->setUserId($_SESSION['userId']);
        $comm->setText($_POST['comment']);
        $comm->setCreationDate(date('Y-m-d'));
        $comm->setTweetId($_POST['btn']);
        $comm->saveToDB($conn);
    }
}
//loading ALL tweets
$tweets = Tweet::loadAllTweets($conn);
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
            echo '<a href="messages.php"><button>Your Messages</button></a>';
            echo '<form action="" method="POST"><input type="submit" name="logout" value="LogOut"/></form>';
        } else {
            echo "Please Log In<br>";
            echo '<a href="register.php"><button>Register</button></a>';
            echo '<a href="login.php"><button>Log in</button></a>';
        }
        ?>

        <!--move it higher-->
        <form action="" method="POST">
            <textarea name="text"></textarea>
            <br>
            <input type="submit" value="Add Tweet"/>
        </form>
        <div>
<?php

foreach ($tweets as $tweet) {
    //loading userName
    $tweetAuthor = User::loadUserById($conn, $tweet->getUserId());
    
    echo 'Tweet # ' . $tweet->getId() . ' of user '
    . $tweetAuthor->getUsername() . '<br>' . $tweet->getText() . '<br>'
    . 'written on ' . $tweet->getCreationDate() . '<br><br>';

    //loading comments for each tweet
    $comments = Comment::loadAllCommentsByTweetId($conn, $tweet->getId());
    if ($comments) {
        foreach ($comments as $value) {
            //loading author's name
            $commAuthor = User::loadUserById($conn, $value->getUserId());
        echo 'comment from ' . $commAuthor->getUsername() . '<br>' . 
             $value->getText() . '<br>' . 'created on: ' . $value->getCreationDate() . '<br><br>';
        }
    }
    //comment form for each tweet
    echo '<form action="" method="POST"><input name="comment" placeholder="write your comment..."/>'
    . '<button name="btn" value="' . $tweet->getId() . '">Add comment</button></form>';
}
?>
        </div>
    </body>
</html>
