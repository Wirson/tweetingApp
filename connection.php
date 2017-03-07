<?php
$db = "twitter";
$host = "localhost";
$user = "root";
$password = "coderslab";
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

//Poniżej napisz kod łączący się z bazą danych
$conn = new PDO($dsn, $user, $password);
if ($conn->errorCode() != null) {
    die("Polaczenie nieudane. Blad: " .
            $conn->errorInfo()[2]);
}