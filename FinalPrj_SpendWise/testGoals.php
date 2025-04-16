<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goals Management</title>
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
        .AddGoal-table{
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
        
        .goal-row:hover .delete-btn {
            right: 0;
        }
        
        .goal-row:hover .edit-btn{
            left: 0;
        }
                

        }
        
    </style>
</head>
<body>
    
    <!-- Contenido principal -->
    <div class="main-content">
        <h1>Goal tracking</h1>
         <br/>
        <h3>Add goal</h3>
         <br/><br/>
        <form class="AddGoal-table" method="POST" action="">
            <input type="text" name="add_goal" class="transaction-input" placeholder="Add goal" required>
            <input type="number" step="0.01" name="target" class="transaction-input" placeholder="Target"required>
            <input type="date" name="deadline" class="transaction-input" required>
            <button type="submit" class="transaction-btn" name="add_new">Add Goal</button>
    	</form>      
    	 <br/><br/>
    	          	 
    
<?php
require_once 'dbConfig.php';
require_once 'Goals.class.php';

$goal = new Goal();
$tabOfGoals = unserialize($goal->getAllGoals($connection));
Goal::displayGoals($tabOfGoals);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_new'])) {
    try {
        //info from form
        $name = $_POST['add_goal'];
        $target = $_POST['target'];
        $deadline = $_POST['deadline'];
        
        $query = "SELECT MAX(goal_id) AS last_id FROM goals";
        $stmt = $connection->query($query);
        $lastId = $stmt->fetchColumn();
        $newId = ($lastId !== false) ? (int)$lastId + 1 : 1;
        
        $goal = new Goal($newId, $name, $target, 0, $deadline, "in_progress");
        
        if ($goal->create($connection)) {
            echo "<script type='text/javascript'>alert('Goal successfully added!');</script>";
            echo "<script type='text/javascript'>window.location.href = '';</script>";}
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_goal'])) {
    var_dump($_POST['delete_goal']);
    try {
        $goalToDelete = new Goal();
        $goalToDelete->setGoalId($_POST['delete_goal']);
        
        if ($goalToDelete->delete($connection)) {
            echo "<script type='text/javascript'>alert('Goal successfully deleted!');</script>";
            echo "<script type='text/javascript'>window.location.href = '';</script>";            
        } else {
            echo "<p style='color: red;'>Failed to delete goal.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['open_edit_modal'])) {
    $goalId = $_POST['edit_goalId'];
    $name = $_POST['edit_name'];
    $targetAmount = $_POST['edit_targetAmount'];
    $currentAmount = $_POST['edit_currentAmount'];
    $deadline = $_POST['edit_deadline'];
    
    // modal
    echo '<div id="editModal" class="modal" style="display:block;">
            <div class="modal-content">
                <h2>Edit Goal</h2>
                <form method="POST">
                    <input type="hidden" name="goal_id" value="' . htmlspecialchars($goalId) . '">
                    <label>Name:</label>
                    <input type="text" name="name" value="' . htmlspecialchars($name) . '" required>
                    <label>Target:</label>
                    <input type="number" step="0.01" name="target" value="' . htmlspecialchars($targetAmount) . '" required>
                    <label>Current Amount:</label>
                    <input type="number" step="0.01" name="current_amount" value="' . htmlspecialchars($currentAmount) . '" required>
                    <label>Deadline:</label>
                    <input type="date" name="deadline" value="' . $deadline . '" required>
                    <button type="submit" name="update_goal">Update</button>
                </form>
            </div>
        </div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_goal'])) {
    try {
        $goalId = $_POST['goal_id'];
        $name = $_POST['name'];
        $target = $_POST['target'];
        $currentAmount = $_POST['current_amount'];
        $deadline = $_POST['deadline'];
        
        $goalToUpdate = new Goal($goalId, $name, $target, $currentAmount, $deadline);
        if($currentAmount >= $target) {
            $goalToUpdate->setStatus("completed");
        }
        
        if ($goalToUpdate   ->update($connection)) {
            echo "<script>alert('Goal successfully updated!'); window.location.href='';</script>";
        } else {
            echo "<p style='color: red;'>Failed to update goal.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>
</div>
</body>
</html>


