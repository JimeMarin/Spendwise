<?php

class Category
{
    private $category_id;
    private $name;  
    
    function __construct($category_id = null, $name = null)
    {
        $this-> category_id = $category_id;
        $this-> name = $name;
    }
    
    public function getCategoryId()
    {
        return $this -> category_id;
    }
    
    public function setCategoryId($cat_id)
    {
        $this -> category_id = $cat_id;
    }
    
    
    
    public function getName()
    {
        return $this -> name;
    }
    public function setName($name)
    {
        $this -> name = $name;
    }
    
      
    
         
    public function __toString()
    {
        $str = "<tr class='category-row'>
                <td class='delete-cell'>
                    <div class='delete-wrapper'>
                        <form method='POST' action='' style='display:inline;'>
                        <button type='submit' onclick='toggleConfirm(this)' class='delete-btn' name='delete_category' value='{$this->getCategoryId()}'>Delete</button>
                        </form>
                    </div>
                </td>
                <td>{$this->getCategoryId()}</td>
                <td>{$this->getName()}</td>  
                              
                <td class='edit-cell'>
                    <div class='edit-wrapper'>
                        <form method='POST' action='' style='display:flex; align-items: center;'> 
                        <input type='text' name='new_name' placeholder='new Name' value = '{$this->getName()}' required >                       
                        <button type='submit' onclick='toggleConfirm(this)' class='edit-btn' name='update_category' value='{$this->getCategoryId()}'>Save</button>
                        </form>
                    </div>
                </td>
                <td></td>
            </tr>";
        return $str;
    }
    
    
    public static function getHeader()
    {
        $str="<table class='categories-table'>";
        $str = "$str<thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Update Category</th>
                        <th></th>
                    </tr>
            </thead>";
        return $str;
    }
    
    public static function getFooter()
    {
        return "</table>";
    }
    
    
    //CRUD
    public function create($connection)
    {
        try {            
            $sqlStmt = "INSERT INTO categories (category_id, NAME) VALUES (:category_id, :name)";
            $result = $connection->prepare($sqlStmt);
            return $result->execute([
                ':category_id' => $this->category_id,
                ':name' => $this->name,
            ]);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    public function update($connection)
    {
        try {
            
            // Asegurarse de que las propiedades sean válidas
            if (empty($this->name) || empty($this->category_id)) {
                throw new Exception("Category name or ID is missing.");
            }
            $sqlStmt = "UPDATE categories SET NAME = :name WHERE category_id = :category_id";
            $stmt = $connection->prepare($sqlStmt);
            $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
     
    public function delete($connection)
    {        
        try {
            $sqlStmt = "DELETE FROM categories WHERE category_id = :category_id";
            $stmt = $connection->prepare($sqlStmt);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }   
    
    
    
    public function getAllCategories($connection)
    {
        $counter = 0;
        $tabCategories = [];
        
        $sqlStmt="Select * from categories";
        
        foreach($connection -> query ($sqlStmt) as $oneRec)
        {
            $category = new Category ();
            $category -> setCategoryId($oneRec["category_id"]);
            $category -> setName($oneRec ["NAME"]);
            $tabCategories[$counter++]= $category;
        }
        return serialize ($tabCategories);
    }
    
    public static function displayCategories ($tabOfCategories)
    {
        echo Category::getHeader();
        foreach ($tabOfCategories as $oneCategory)
            echo $oneCategory;
            
            echo Category::getFooter();
    }
}

?>