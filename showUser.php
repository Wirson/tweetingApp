<?php
session_start();
require 'src/connection.php';
require 'src/User.php';
require 'src/Tweet.php';
require 'src/Comment.php';

//checking log in state
if (isset($_SESSION['userId'])) {
    $loggedUser = User::loadUserById($conn, $_SESSION['userId']);
} else {
    exit(header("Location: index.php"));
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
    exit('User deleted!');
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
//saving comment
if (isset($_POST['comment']) && !empty($_POST['comment']) && strlen($_POST['comment']) < 60) {
        $comm = new Comment;

        $comm->setUserId($_SESSION['userId']);
        $comm->setText($_POST['comment']);
        $comm->setCreationDate(date('Y-m-d'));
        $comm->setTweetId($_POST['btn']);
        $comm->saveToDB($conn);
    }
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
        <a href="messages.php"><button>Your Messages</button></a>
        <form action="" method="POST">
            <input type="submit" name="logout" value="LogOut"/>
        </form>
        <h1>Update your data!</h1>
        <p>Hi <?php echo $_SESSION['userName']; ?>!</p>
        <p>Your ID: <?php echo $_SESSION['userId']; ?></p>
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
        //showing all user's posts
        $tweets = Tweet::loadAllTweetsByUserId($conn, $loggedUser->getId());
        if ($tweets) {
            foreach ($tweets as $value) {
                $id = $value->getId();
                echo '<a href="tweetDescription.php?id=' . $id . '">' . 'Tweet # ';
                echo $id . '</a>' . '<br>' . $value->getText() . '<br>';
                //loading comments for each tweet
                $comments = Comment::loadAllCommentsByTweetId($conn, $value->getId());
                if ($comments) {
                    foreach ($comments as $value) {
                        //loading author's name
                        $commAuthor = User::loadUserById($conn, $value->getUserId());
                        echo '<br>comment from ' . $commAuthor->getUsername() . '<br>' .
                        $value->getText() . '<br>' . 'created on: ' . $value->getCreationDate() . '<br>';
                    }
                }
                //comment form for each tweet
                echo '<form action="" method="POST"><input name="comment" placeholder="write your comment..."/>'
                . '<button name="btn" value="' . $value->getId() . '">Add comment</button></form>';
                echo '</div>';
            }
        } else {
            echo 'no tweets!';
        }
        ?>
    </body>
</html>
