<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$dbname = "dbspendwise";

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die("Error: " . $exception->getMessage());
}
?>