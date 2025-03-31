<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Management</title>
</head>
<body>
    <h2>Manage Expenses</h2>
    
<?php
require_once 'dbConfig.php';

listAllExpensess();

function listAllExpensess() {
    global $connection;
    
    $sqlStmt = "SELECT * FROM expenses";
    $queryId = mysqli_query($connection, $sqlStmt);
    $nbRows = mysqli_num_rows($queryId);
    
    if ($nbRows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Expense ID</th><th>Category ID</th><th>Amount</th><th>Description</th><th>Date</th></tr>";
        
        while ($row = mysqli_fetch_assoc($queryId)) {
            echo "<tr><td>{$row['expense_id']}</td><td>{$row['category_id']}</td><td>{$row['amount']}</td><td>{$row['description']}</td><td>{$row['expense_date']}</td></tr>";
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



