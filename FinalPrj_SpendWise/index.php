<!DOCTYPE html>
<html>
<head>
  <title>Spend Wise</title>
  <?php
      require_once 'dbConfig.php';
      echo "<h2>Manipulate the dataBase $dbName of the user $user </h2><br/>";
  ?>        
</head>
<body>
	<a href="opCategory.php?op=1">Category</a><br/>
	<a href="opBudget.php?op=1">Budget</a><br/>
	<a href="opExpense.php?op=2">Expenses</a><br/>
	<a href="opFinGoal.php?op=3">Financial Goals</a><br/>
</body>
</html>


