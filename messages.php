<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
}
require 'src/connection.php';
require 'src/User.php';
require 'src/Message.php';
//loading messages
$messages = Message::loadAllMessagesByRecipient($conn, $_SESSION['userId']);
var_dump($_POST);
if (isset($_POST['text'])) {
        $msg = new Message;
        
        $msg->setSender($_SESSION['userId']);
        $msg->setRecipient($_POST['recipient']);
        $msg->setText($_POST['text']);
        $msg->setCreationDate(date('Y-m-d'));
        $msg->setStatus(0); //0 is default unread
        $msg->saveToDB($conn);
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
        <a href="index.php"><button>Main Page</button></a>
        <a href="showUser.php"><button>Your Profile</button></a>
        <h4>Hi <?php echo $_SESSION['userName']; ?>! Messages:</h4>
        <?php
        if ($messages) {
            foreach ($messages as $msg) {
                $senderId = User::loadUserById($conn, $msg->getSender());
                echo 'Message from ' . $senderId->getUsername() . ' sent on: ' . $msg->getCreationDate() . '<br>' .
                $msg->getText() . '<br><br>';
            }
        } else {
            echo 'No Messages!';
        }
        ?>
        <h2>Send a Message</h2>
        <form action="" method="post">            
            <textarea name="text"></textarea>
            <br>
            <select name="recipient">
            <?php
            $users = User::loadAllUsers($conn);
            foreach ($users as $usr) {
                echo '<option value=' . $usr->getId() . '>' . $usr->getUsername() . '</option>';
            }
            
            ?>
            </select>
            <input type="submit" value="Send!"/>        
        </form>
    </body>
</html>