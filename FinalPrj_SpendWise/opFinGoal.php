<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Management</title>
</head>
<body>
    <h2>Manage Financial Goals</h2>
    
<?php
require_once 'dbConfig.php';

listAllFinGoals();

function listAllFinGoals() {
    global $connection;
    
    $sqlStmt = "SELECT * FROM goals";
    $queryId = mysqli_query($connection, $sqlStmt);
    $nbRows = mysqli_num_rows($queryId);
    
    if ($nbRows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Goal ID</th><th>Name</th><th>Target Amount</th><th>Current Amount</th><th>Deadline</th><th>Status</th></tr>";
        
        while ($row = mysqli_fetch_assoc($queryId)) {
            echo "<tr><td>{$row['goal_id']}</td><td>{$row['name']}</td><td>{$row['target_amount']}</td><td>{$row['current_amount']}</td><td>{$row['deadline']}</td><td>{$row['status']}</td></tr>";
        }
        
        echo "</table>";
    } else {
        echo "No categories available.<br/>";
    }
    
    mysqli_close($connection);
}
?>  
</body>
</html>




