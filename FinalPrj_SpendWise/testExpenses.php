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
        
        
        /* Tabla de transacciones */
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
        
        .income {
            color: #4CAF50;
        }
        
        .expense {
            color: #f44336;
        }
        
        .expense-row {
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }
        
        .expense-row:hover {
            background-color: #f0f0f0;
        }
        
        .expense-data {
            display: flex;
            justify-content: space-around;
            padding: 10px;
        }
        
/*         .delete-option { */
/*             position: absolute; */
/*             top: 0; */
/*             right: -100px; /* Initially hidden */ */
/*             width: 100px; */
/*             height: 100%; */
/*             background-color: rgba(255, 0, 0, 0.8); */
/*             color: white; */
/*             display: flex; */
/*             justify-content: center; */
/*             align-items: center; */
/*             transition: right 0.3s ease; */
/*         } */
        
/*         .expense-row:hover .delete-option { */
/*             right: 0; /* Show on hover */ */
/*         } */
        
/*         .delete-option button { */
/*             background-color: transparent; */
/*             border: none; */
/*             color: white; */
/*             cursor: pointer; */
/*             font-size: 16px; */
        

        }
        
    </style>
</head>
<body>
    
    <!-- Contenido principal -->
    <div class="main-content">
        <h1>Expenses tracking</h1>
        
        
        <h2>Recent Transactions</h2>
        <p><strong><script>document.write(new Date().toLocaleString('es-ES',{month:'short',year:'numeric'}));</script></strong></p>
        
        <h3>Add expenses</h3>
        <form class="transactions-table" method="POST" action="">
        <input type="text" name="add_expenses" placeholder="Add expenses" required>
        <input type="date" name="expense_date" id="expense_date" required>
        <select name="category_id" id="category_id" required>
        <?php
        // Obtener las categorías de la base de datos
        $sqlCategories = "SELECT category_id, NAME FROM categories";
        $resultCategories = $connection->query($sqlCategories);

        // Mostrar las categorías como opciones en el select
        if ($resultCategories && $resultCategories->rowCount() > 0) {
            while ($rowCategory = $resultCategories->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$rowCategory['category_id']}'>{$rowCategory['NAME']}</option>";
            }
        } else {
            echo "<option value=''>No categories available</option>";
        }
        ?>
   		</select>
        <input type="number" name="add_amount" placeholder="Amount" required>
        <button type="submit" name="add_new">Add Expense</button>
    	</form>      
    
<?php
require_once 'dbConfig.php';
require_once 'Expenses.class.php';

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



?>  
</div>
</body>
</html>



