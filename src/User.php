<?php

require_once 'connection.php';

class User {

    const NON_EXISTING_ID = -1;

    private $id = self::NON_EXISTING_ID;
    private $username;
    private $email;
    private $hashPass;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getHashPass() {
        return $this->hashPass;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPass($pass) {
        $this->hashPass = password_hash($pass, PASSWORD_BCRYPT);
    }

    public function saveToDB(PDO $conn) {

        if ($this->id == self::NON_EXISTING_ID) {

            $stmt = $conn->prepare('INSERT INTO Users(username, email, hash_pass) VALUES (:username, :email, :pass)');
            $result = $stmt->execute(
                    [
                        'username' => $this->username,
                        'email' => $this->email,
                        'pass' => $this->hashPass
                    ]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare(
                    'UPDATE Users SET username=:username, email=:email, hash_pass=:hash_pass WHERE id=:id'
            );
            $result = $stmt->execute(
                    [
                        'username' => $this->username,
                        'email' => $this->email,
                        'hash_pass' => $this->hashPass,
                        'id' => $this->id
                    ]
            );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    public static function loadUserByEmail(PDO $conn, $mail) {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:mail');
        $result = $stmt->execute(['mail' => $mail]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    public static function loadUserById(PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    public static function loadAllUsers(PDO $conn) {
        $sql = "SELECT * FROM Users";
        $ret = [];
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hash_pass'];
                $loadedUser->email = $row['email'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    public function delete(PDO $conn) {
        if ($this->id != self::NON_EXISTING_ID) {
            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = self::NON_EXISTING_ID;
                return true;
            }
            return false;
        }
        return true;
    }

    public function passVerify($pass) {
        return password_verify($pass, $this->hashPass);
    }

}
