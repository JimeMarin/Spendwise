<?php

session_start();

if (isset($_SESSION["ID"]))
{
    $s_id = session_id();
    $name = $_SESSION["NAME"];
    echo "Welcome $name";
    /* echo "&nbsp&nbsp <a href = 'endSession.php'> Log out </a>";
    echo'
        <form action="endSession.php" method="post" style="display:inline;">
            <button type="submit" class="btn btn-dark ms-2">Log Out</button>
        </form>
    '; */
}
else 
    echo "Not Connected.";
?>