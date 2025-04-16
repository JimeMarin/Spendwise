<?php

include_once 'heading.php';
include_once 'dbConfig.php';
include_once 'User.class.php';

try{
    $connection = new PDO("mysql:host=$host;dbname=$dbname",$user, $pass);
    
    $oneUser = new User();
    
    if (!empty($_POST["email"])) {
        $email = $_POST["email"];
        $oneUser = new User();
        $oneUser->setEmail($email);
        $oneUser = unserialize($oneUser->getUserByEmail($connection));
    }
    
    if (!empty($oneUser))
    {
        $_SESSION["EXIST"]="Y";
        $_SESSION["ID"]=$oneUser->getUserID();
        $_SESSION["NAME"]=$oneUser->getName();
        $_SESSION["EMAIL"]=$oneUser->getEmail();
        $_SESSION["USER_PASSWORD"] = $oneUser->getPassword();
    }
    else
        $_SESSION["EXIST"]="N";
}
catch (PDOException $e){
    echo $e->getMessage();
}
?>