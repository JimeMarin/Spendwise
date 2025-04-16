<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    <?php require_once 'Topbar.php'; require_once 'heading.php';?>
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
        
        .main-content {
            padding: 20px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #444;
            margin: 20px 0 15px 0;
            font-size: 18px;
        }
         
        /*Add categ*/    
        .AddCat-table{
            display: flex;
            justify-content: space-around;
            border-color: #416847;
        }
        
                .transaction-btn{
            padding: 8px 15px;
        }
        
        .transaction-btn:hover{
            background-color: #416847;
            color: white;
        }
        
        
        .transaction-input{
            padding: 8px 15px;
        }
        
        /*show - edit catg*/
        
        .categories-table{
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            
        }
        
        .categories-table th, .categories-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .categories-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }
        
        .categories-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .delete-cell, .edit-cell {
              position: relative;
              overflow: hidden;
              width: 80px;
              padding: 0;
        }
        
        .delete-btn {
              position: absolute;
              right: -80px;
              top: 0;
              height: 100%;
              width: 80px;
              background-color: #ff3b30;
              color: white;
              border: none;
              transition: right 0.3s ease;
              cursor: pointer;
        }
        
         .edit-btn {
              position: absolute;
              left: -80px;
              top: 0;
              height: 100%;
              width: 80px;
              background-color: #416847;
              color: white;
              border: none;
              transition: left 0.3s ease;
              cursor: pointer;
        } 
        
        .category-row:hover .delete-btn {
            right: 0;
        }
         
         .category-row:hover .edit-btn{
            left: 0;
        }
          

        
        
    </style>
</head>
<body>
<div class="main-content">
    <h1>Categories</h1>
    <!-- Form add categ-->
    <br/>
        <h3>Add categories</h3>
    <br/><br/>    
    <form class="AddCat-table" method="POST" action="">
        <input type="text" name="new_category" class="transaction-input"placeholder="New category" required>
        <button type="submit" class="transaction-btn" name="add_category">Add Category</button>
    </form>  
    <br/><br/>  
<?php
require_once 'dbConfig.php';
require_once 'Category.class.php';
require_once 'heading.php';

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
</div>
</body>
</html>