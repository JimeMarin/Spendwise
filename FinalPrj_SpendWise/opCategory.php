<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
</head>
<body>
    <h2>Manage Categories</h2>
    
<?php
require_once 'dbConfig.php';
require_once 'Category.class.php';

listAllCategories();

function listAllCategories() {
    global $connection;
    
    $sqlStmt = "SELECT * FROM categories";
    $queryId = mysqli_query($connection, $sqlStmt);
    $nbRows = mysqli_num_rows($queryId);
    
    if ($nbRows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Category ID</th><th>Name</th></tr>";
        
        while ($row = mysqli_fetch_assoc($queryId)) {
            echo "<tr><td>{$row['category_id']}</td><td>{$row['name']}</td></tr>";
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


