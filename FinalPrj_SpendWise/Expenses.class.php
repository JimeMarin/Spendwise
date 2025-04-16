<?php

class Expenses
{
    private $expense_id;
    private $category_id;
    private $category_name;
    private $amount;
    private $description;
    private $expense_date;
    
    function __construct($category_id = null, $amount = null, $description = null, $expense_date = null, $category_name = null)
    {
        $this-> category_id = $category_id;
        $this-> amount = $amount;
        $this-> description = $description;
        $this-> expense_date = $expense_date;
        $this-> category_name = $category_name;
    }
    
    public function getExpenseId()
    {
        return $this -> expense_id;
    }
    
    public function setExpenseId($expense_id)
    {
        $this -> expense_id = $expense_id;
    }
    
    
    public function getCategoryId()
    {
        return $this -> category_id;
    }
    
    public function setCategoryId($category_id)
    {
        $this -> category_id = $category_id;
    }
    
        
    public function getCategoryName()
    {
        return $this -> category_name;
    }
    public function setCategoryName($category_name)
    {
        $this -> category_name = $category_name;
    }
    
    
    public function getAmount()
    {
        return $this -> amount;
    }
    public function setAmount($amount)
    {
        $this -> amount = $amount;
    }
    
    
    public function getDescription()
    {
        return $this -> description;
    }
    public function setDescription($description)
    {
        $this -> description = $description;
    }
    
    
    public function getExpenseDate()
    {
        return $this -> expense_date;
    }
    public function setExpenseDate($expense_date)
    {
        $this -> expense_date = $expense_date;
    }
      
    public function getAllCategories ()
    {
        $sqlCategories = "SELECT category_id, NAME FROM categories";
        $resultCategories = $connection->query($sqlCategories);
        
        // categorias de la db
        if ($resultCategories && $resultCategories->rowCount() > 0) {
            while ($rowCategory = $resultCategories->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$rowCategory['category_id']}'>{$rowCategory['NAME']}</option>";
            }
        } else {
            echo "<option value=''>No categories available</option>";
        }
    }
         
    public function __toString()
    {
        
        $str = "<tr class='expense-row'>                               
                <td class='delete-cell'>
                    <div class='delete-wrapper'>
                        <form method='POST' action=''>
                            <button type='submit' onclick='toggleConfirm(this)' class='delete-btn' name='delete_expense' value='{$this->getExpenseId()}'>Delete</button>
                        </form>
                    </div>
                </td>
                <td>{$this->getDescription()}</td>
                <td>{$this->getExpenseDate()}</td>
                <td>{$this->getCategoryName()}</td>
                <td>{$this->getAmount()}</td>
                <td class='edit-cell'>
                    <div class='edit-wrapper'>
                        <form method='POST' action=''>
                            <input type='hidden' name='edit_expense_id' value='{$this->getExpenseId()}'>
                            <input type='hidden' name='edit_description' value='{$this->getDescription()}'>
                            <input type='hidden' name='edit_date' value='{$this->getExpenseDate()}'>
                            <input type='hidden' name='edit_category_id' value='{$this->getCategoryId()}'>
                            <input type='hidden' name='edit_amount' value='{$this->getAmount()}'>
                            <button type='submit' onclick='toggleConfirm(this)' class='edit-btn' name='open_edit_modal' value='{$this->getExpenseId()}'>Edit</button>
                        </form>
                    </div>
                </td>
                </tr>";
        return $str;
    }
    
    
    public static function getHeader()
    {
        $str="<table class='transactions-table'>";
        $str = "$str<thead>
                <tr>
                    <th></th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead><tbody>";
        return $str;
    }
    
    public static function getFooter()
    {
        return "</tbody></table>";
    }
    
    
    //CRUD
    public function create($connection)
    {
        try {
            
            $sqlStmt = "INSERT INTO expenses (category_id, amount, description, expense_date)
                    VALUES (:category_id, :amount, :description, :expense_date)";
            
            
            $stmt = $connection->prepare($sqlStmt);
                        
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindParam(':expense_date', $this->expense_date, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function update($connection)
    {
        try {
            
            if (empty($this->description) || empty($this->expense_date)|| empty($this->category_id)|| empty($this->amount)) {
                throw new Exception("information is missing.");
            }
            $sqlStmt = "UPDATE expenses
            SET description = :description, expense_date = :expense_date, category_id = :category_id, amount = :amount
            WHERE expense_id = :expense_id";
            $stmt = $connection->prepare($sqlStmt);
            $stmt->bindParam(':description', $this->description, PDO::PARAM_STR); //bindParam sirve para vincular la var de php con a un ph con nombre en el SQLSTMT
            $stmt->bindParam(':expense_date', $this->expense_date, PDO::PARAM_STR);//PDO:PARAM...etc ayuda a optimizar pq se especifica el tipo que se espera
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindParam(':expense_id', $this->expense_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
     
    public function delete($connection)
    {
        try {
            $sqlStmt = "DELETE FROM expenses WHERE expense_id = :expense_id";
            $stmt = $connection->prepare($sqlStmt);
            $stmt->bindParam(':expense_id', $this->expense_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    } 
    
    
    
    public function getAllExpenses($connection)
    {   
        try {
        $sqlStmt= "SELECT expenses.description, expenses.amount, expenses.expense_date, NAME AS category_name, expenses.category_id, expenses.expense_id
                  FROM
                    expenses
                  INNER JOIN
                    categories
                  ON
                    expenses.category_id = categories.category_id";
        
        
        $tabExpenses = [];
        
        foreach($connection -> query ($sqlStmt) as $oneRec)
        {
            $expenses = new Expenses (
                $oneRec["category_id"],
                $oneRec["amount"],
                $oneRec["description"],
                $oneRec["expense_date"],
                $oneRec["category_name"]
                );
            $expenses->setExpenseId($oneRec["expense_id"]); 
            $tabExpenses[]= $expenses;
        }
        return serialize ($tabExpenses);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    
    public static function displayExpenses ($tabOfExpenses)
    {
        echo Expenses::getHeader();
        if (is_array($tabOfExpenses)) {
        foreach ($tabOfExpenses as $oneExpense)
            echo $oneExpense;
        } else {
            echo "<tr><td colspan='4'>No expenses found.</td></tr>";
        }
            echo Expenses::getFooter();
    }
    
    
}

?>