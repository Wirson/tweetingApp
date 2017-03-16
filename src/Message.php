<?php

require 'connection.php';

class Message {
    const NON_EXISTING_ID = -1;

    private $id;
    private $sender;
    private $recipient;
    private $text;
    private $creationDate;
    private $status;
    
    public function __construct() {
        $this->id = -1;
        $this->sender = null;
        $this->recipient = null;
        $this->text = null;
        $this->creationDate = null;
        $this->status = 0;
    }
    public function getId() {
        return $this->id;
    }

    public function getSender() {
        return $this->sender;
    }

    public function getRecipient() {
        return $this->recipient;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getStatus() {
        return $this->status;
    }
    
    public function setSender($sender) {
        $this->sender = $sender;
    }

    public function setRecipient($recipient) {
        $this->recipient = $recipient;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public static function loadAllMessagesByRecipient(PDO $conn, $recipient) {
        $sql = "SELECT * FROM Message WHERE recipient=:recipient ORDER BY creationDate DESC";
        $stmt = $conn->prepare($sql);
        $ret = $stmt->execute([
            'recipient' => $recipient
        ]);
        $result = [];
        if ($ret !== false && $stmt->rowCount() != 0) {
            foreach ($stmt as $row) {
                $loadedMess = new Messages();
                $loadedMess->id = $row['id'];
                $loadedMess->sender = $row['sender'];
                $loadedMess->recipient = $row['recipient'];
                $loadedMess->text = $row['text'];
                $loadedMess->creationDate = $row['creationDate'];
                $loadedMess->status = $row['status'];
                $result [] = $loadedMess;
            }
        } else {
            return false;
        }
        return $result;    
    }
    
    public function saveToDB(PDO $conn) {

        if ($this->id == self::NON_EXISTING_ID) {

            $stmt = $conn->prepare('INSERT INTO Message(sender, recipient, text, creationDate, status) VALUES (:sender, :recipient, :text, :creationDate, :status)');
            $result = $stmt->execute(
                    [
                        'sender' => $this->sender,
                        'recipient' => $this->recipient,
                        'text' => $this->text,
                        'creationDate' => $this->creationDate,
                        'status' => $this->status
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