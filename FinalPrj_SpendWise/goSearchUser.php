<?php

include_once 'heading.php';

if (!empty($_POST["email"]) && !empty($_POST["password"])) 
{
    include "findUser.php";
    
    if ($_SESSION["EXIST"] == "Y") 
    {
        if (md5($_POST["password"]) === $_SESSION["USER_PASSWORD"]) 
        {
           header("Location: showUserSession.php");
        } 
        else 
        {
            
            header("Location: fileError.php?error=Incorret password.");
        }
    } 
    else 
    {
        header("Location: fileError.php?error=User not found.");
    }
} 
else 
{
    header("Location: login.php");
}
    
?>
