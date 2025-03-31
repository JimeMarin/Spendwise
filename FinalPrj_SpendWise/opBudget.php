<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Management</title>
</head>
<body>
    <h2>Manage Budget</h2>
    
<?php
require_once 'dbConfig.php';

listAllBudgets();

function listAllBudgets() {
    global $connection;
    
    $sqlStmt = "SELECT * FROM budget";
    $queryId = mysqli_query($connection, $sqlStmt);
    $nbRows = mysqli_num_rows($queryId);
    
    if ($nbRows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Budget ID</th><th>Category ID</th><th>Amount</th><th>Period</th><th>Start Date</th><th>End Date</th></tr>";
        
        while ($row = mysqli_fetch_assoc($queryId)) {
            echo "<tr><td>{$row['budget_id']}</td><td>{$row['category_id']}</td><td>{$row['amount']}</td><td>{$row['period']}</td><td>{$row['start_date']}</td><td>{$row['end_date']}</td></tr>";
        }
        
        echo "</table>";
    } else {
        echo "No budgets available.<br/>";
    }
    
    mysqli_close($connection);
}
?>  
</body>
</html>


