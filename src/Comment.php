<?php

require 'connection.php';

class Comment {

    const NON_EXISTING_ID = -1;

    private $id;
    private $userId;
    private $tweetId;
    private $creationDate;
    private $text;

    public function __construct() {
        $this->id = -1;
        $this->userId = null;
        $this->tweetId = null;
        $this->creationDate = null;
        $this->text = null;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTweetId() {
        return $this->tweetId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getText() {
        return $this->text;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setTweetId($tweetId) {
        $this->tweetId = $tweetId;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public static function loadCommentById(PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM Comment WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->tweetId = $row['tweetId'];
            $loadedComment->text = $row['text'];
            $loadedComment->creationDate = $row['creationDate'];
            return $loadedComment;
        }
        return null;
    }

    public static function loadAllCommentsByTweetId(PDO $conn, $tweetId) {
        $sql = "SELECT * FROM Comment WHERE tweetId=:tweetId ORDER BY creationDate DESC";
        $stmt = $conn->prepare($sql);
        $ret = $stmt->execute([
            'tweetId' => $tweetId
        ]);
        $result = [];
        if ($ret !== false && $stmt->rowCount() != 0) {
            foreach ($stmt as $row) {
                $loadedComms = new Comment();
                $loadedComms->id = $row['id'];
                $loadedComms->userId = $row['userId'];
                $loadedComms->tweetId = $row['tweetId'];
                $loadedComms->text = $row['text'];
                $loadedComms->creationDate = $row['creationDate'];
                $result [] = $loadedComms;
            }
        } else {
            return false;
        }
        return $result;
    }

    public function saveToDB(PDO $conn) {

        if ($this->id == self::NON_EXISTING_ID) {

            $stmt = $conn->prepare('INSERT INTO Comment(userId, tweetId, text, creationDate) VALUES (:userId, :tweetId, :text, :creationDate)');
            $result = $stmt->execute(
                    [
                        'userId' => $this->userId,
                        'tweetId' => $this->tweetId,
                        'text' => $this->text,
                        'creationDate' => $this->creationDate
                    ]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        }

        return false;
    }

}