<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Page</title>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION["ID"]))
    echo "<a href = 'index.php'> Open new user session </a>";
    else
        echo "<a href = 'showUserSession.php'> Go to the current session </a>";
?>
</body>
</html>