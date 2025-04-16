<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Management</title>
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
                
        /* Contenido principal */
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
        
        /*Tabla Add expenses*/
        .AddEx-table{
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
        
        /* Tabla de Expenses */
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
       
        .transactions-table th, .transactions-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .transactions-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }
        
        .transactions-table tr:hover {
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
        
        .expense-row:hover .delete-btn {
            right: 0;
        }
        
        .expense-row:hover .edit-btn{
            left: 0;
        }
                

        }
        
    </style>
</head>
<body>
    
    <!-- Contenido principal -->
    <div class="main-content">
        <h1>Expenses tracking</h1>
        
        
        <h2>Recent Transactions</h2>
        <p><strong><script>document.write(new Date().toLocaleString('es-ES',{month:'short',year:'numeric'}));</script></strong></p>
         <br/>
        <h3>Add expenses</h3>
         <br/><br/>
        <form class="AddEx-table" method="POST" action="">
        <input type="text" name="add_expenses" class="transaction-input" placeholder="Add expenses" required>
        <input type="date" name="expense_date" class="transaction-input" id="expense_date" required>
        <select name="category_id" class="transaction-input" id="category_id" required>
        <?php
        
        
        $sqlCategories = "SELECT category_id, NAME FROM categories";
        $resultCategories = $connection->query($sqlCategories);

        //select
        if ($resultCategories && $resultCategories->rowCount() > 0) {
            while ($rowCategory = $resultCategories->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$rowCategory['category_id']}'>{$rowCategory['NAME']}</option>";
            }
        } else {
            echo "<option value=''>No categories available</option>";
        }
        ?>
   		</select>
        <input type="number" name="add_amount" class="transaction-input" placeholder="Amount" required>
        <button type="submit" class="transaction-btn" name="add_new">Add Expense</button>
    	</form>      
    	 <br/><br/>
    	          	 
    
<?php
require_once 'dbConfig.php';
require_once 'Expenses.class.php';
require_once 'heading.php';

$expenses = new Expenses();
$tabOfExpenses= unserialize($expenses->getAllExpenses($connection));
Expenses::displayExpenses($tabOfExpenses);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_new'])) {
    try {
        //info from form
        $description = $_POST['add_expenses'];
        $date = $_POST['expense_date'];
        $category = $_POST['category_id'];
        $amount = $_POST['add_amount']; 
        
        $expense = new Expenses ($category, $amount, $description, $date);
        
        if ($expense->create($connection)) {
            echo "<script type='text/javascript'>alert('Category successfully added!');</script>";
            echo "<script type='text/javascript'>window.location.href = '';</script>";}
            
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_expense'])) {
    var_dump($_POST['delete_expense']);
    try {
        $expenseToDelete = new Expenses();
        $expenseToDelete->setExpenseId($_POST['delete_expense']);
        
        if ($expenseToDelete->delete($connection)) {
            echo "<script type='text/javascript'>alert('Expense successfully deleted!');</script>";
            echo "<script type='text/javascript'>window.location.href = '';</script>";            
        } else {
            echo "<p style='color: red;'>Failed to delete category.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}

$expenses = new Expenses();
$tabOfExpenses = unserialize($expenses->getAllExpenses($connection));
$expenseId = $description = $date = $categoryId = $amount = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['open_edit_modal'])) {
    
    $expenseId = $_POST['edit_expense_id'];
    $description = $_POST['edit_description'];
    $date = $_POST['edit_date'];
    $categoryId = $_POST['edit_category_id'];
    $amount = $_POST['edit_amount'];
    
    // modal
    echo '<div id="editModal" class="modal" style="display:block;">
            <div class="modal-content">
                <h2>Edit Expense</h2>
                <form method="POST">
                    <input type="hidden" name="expense_id" value="' . htmlspecialchars($expenseId) . '">
                    <label>Description:</label>
                    <input type="text" name="description" value="' . htmlspecialchars($description) . '" required>
                    <label>Date:</label>
                    <input type="date" name="expense_date" value="' . $date . '" required>
                    <label>Category:</label>
                    <select name="category_id" required>';
    
    
    $sqlCategories = "SELECT category_id, NAME FROM categories";
    $resultCategories = $connection->query($sqlCategories);
    
    if ($resultCategories && $resultCategories->rowCount() > 0) {
        while ($rowCategory = $resultCategories->fetch(PDO::FETCH_ASSOC)) {
            
            $selected = ($rowCategory['category_id'] == $categoryId) ? 'selected' : '';
            echo "<option value='{$rowCategory['category_id']}' {$selected}>{$rowCategory['NAME']}</option>";
        }
    } else {
        echo "<option value=''>No categories available</option>";
    }
    
    echo '</select>
                    <label>Amount:</label>
                    <input type="number" step="0.01" name="amount" value="' . htmlspecialchars($amount) . '" required>
                    <button type="submit" name="update_expense">Update</button>
                </form>
            </div>
        </div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_expense'])) {
    try {
        $expenseId = $_POST['expense_id'];
        $description = $_POST['description'];
        $date = $_POST['expense_date'];
        $category = $_POST['category_id'];
        $amount = $_POST['amount'];
        
        $expenseToUpdate = new Expenses();
        $expenseToUpdate->setExpenseId($expenseId);
        $expenseToUpdate->setDescription($description);
        $expenseToUpdate->setExpenseDate($date);
        $expenseToUpdate->setCategoryId($category);
        $expenseToUpdate->setAmount($amount);
        
        if ($expenseToUpdate->update($connection)) {
            echo "<script>alert('Expense successfully updated!'); window.location.href='';</script>";
        } else {
            echo "<p style='color: red;'>Failed to update expense.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}

?>
</div>
</body>
</html>