<?php

require 'connection.php';

class Tweet {

    const NON_EXISTING_ID = -1;

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = null;
        $this->text = null;
        $this->creationDate = null;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public static function loadTweetById(PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM Tweet WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;
        }
        return null;
    }

    public static function loadAllTweetsByUserId(PDO $conn, $userId) {
        $sql = "SELECT * FROM Tweet WHERE userId=:userId";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'userId' => $userId
        ]);
        $result = [];
        if ($stmt !== false && $stmt->rowCount() != 0) {
            foreach ($stmt as $row) {
                $loadedTweets = new Tweet();
                $loadedTweets->id = $row['id'];
                $loadedTweets->userId = $row['userId'];
                $loadedTweets->text = $row['text'];
                $loadedTweets->creationDate = $row['creationDate'];
                $result [] = $loadedTweets;
            }
        }
        return $result;
    }

    public static function loadAllTweets(PDO $conn) {
        $sql = "SELECT * FROM Tweet ORDER BY creationDate DESC";
        $ret = [];
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweets = new Tweet();
                $loadedTweets->id = $row['id'];
                $loadedTweets->userId = $row['userId'];
                $loadedTweets->text = $row['text'];
                $loadedTweets->creationDate = $row['creationDate'];
                $ret[] = $loadedTweets;
            }
        }
        return $ret;
    }

    public function saveToDB(PDO $conn) {

        if ($this->id == self::NON_EXISTING_ID) {

            $stmt = $conn->prepare('INSERT INTO Tweet(userId, text, creationDate) VALUES (:userId, :text, :creationDate)');
            $result = $stmt->execute(
                    [
                        'userId' => $this->userId,
                        'text' => $this->text,
                        'creationDate' => $this->creationDate
                    ]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare(
                    'UPDATE Tweet SET id:id, userId:userId, text:text, creationDate:creationDate WHERE id=:id'
            );
            $result = $stmt->execute(
                    [
                        'userId' => $this->userId,
                        'text' => $this->text,
                        'creationDate' => $this->creationDate,
                        'id' => $this->id
                    ]
            );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    public function deleteTweet(PDO $conn) {
        if ($this->id != self::NON_EXISTING_ID) {
            $stmt = $conn->prepare('DELETE FROM Tweet WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = self::NON_EXISTING_ID;
                return true;
            }
            return false;
        }
        return true;
    }

}
