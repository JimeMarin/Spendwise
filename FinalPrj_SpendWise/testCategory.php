
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    <?php require_once 'Topbar.php';?>
    <style>
    * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            padding-top: 60px;
            padding-bottom: 70px;
        }
        
        tr:hover .actions {
          opacity: 1;
          transform: translateX(0);
        }
        .actions {
          opacity: 0;
          transform: translateX(10px);
          transition: all 0.3s ease;
        }
            
        
    </style>
</head>
<body>
    <h1>Categories</h1>
    <!-- Form add categ-->
    <form method="POST" action="">
        <input type="text" name="new_category" placeholder="New category" required>
        <button type="submit" name="add_category">Add Category</button>
    </form>  
      
<?php
require_once 'dbConfig.php';
require_once 'Category.class.php';

$category = new Category();
$tabOfCategories= unserialize($category->getAllCategories($connection));
Category::displayCategories($tabOfCategories);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    try {
        
        $name = $_POST['new_category'];
        
        $query = "SELECT MAX(category_id) AS last_id FROM categories";
        $stmt = $connection->query($query);
        $lastId = $stmt->fetchColumn();
        
        
        $newId = ($lastId !== false) ? (int)$lastId + 1 : 1;
        
        
        $category = new Category($newId, $name);
        
        
        while ($category->create($connection)) {
            echo "<script type='text/javascript'>alert('Category successfully added!');</script>";           
            echo "<script type='text/javascript'>window.location.href = '';</script>";}
         
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    try {
        $categoryToDelete = new Category();
        $categoryToDelete->setCategoryId($_POST['delete_category']); 
        
        if ($categoryToDelete->delete($connection)) {
            echo "<script type='text/javascript'>alert('Category successfully deleted!');</script>";
            
            echo "<script type='text/javascript'>window.location.href = '';</script>";
        } else {
            echo "<p style='color: red;'>Failed to delete category.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    try {
        $categoryIdToUpdate = $_POST['update_category'];
        $newName = $_POST['new_name'];
               
        $categoryToUpdate = new Category();
        $categoryToUpdate ->setCategoryId($categoryIdToUpdate);
        $categoryToUpdate ->setName($newName);
        
        
        if ($categoryToUpdate->update($connection)) {
            echo "<script type='text/javascript'>alert('Category successfully updated!');</script>";
            
            echo "<script type='text/javascript'>window.location.href = '';</script>";
        } else {
            echo "<p style='color: red;'>Failed to update category.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}


?>

</body>
</html>

