<html>
<div style="background: red; height: 300px; width: 100%">
<?php
$error =$_GET["error"];
echo "$error <br/>";

if ($error=='Teacher not found')
    echo "<a href='login.php'> New Search </a>";
else 
    echo "<a href='showUserSession.php'> Show User Session </a><br/>";
?>
</div>
</html>
