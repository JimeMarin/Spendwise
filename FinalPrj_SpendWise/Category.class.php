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
    
    public function getName()
    {
        return $this -> name;
    }
    
    public function setName($name)
    {
        $this -> name = $name;
    }
     
    public static function getHeader()
    {
        $str = "<table border='1'>";
        $str .= "<tr><th>Category ID</th><th>Name</th></tr>";
        return $str;
        
    }
    public static function getFooter()
    {
        return"</table>";
    }
    
    public function __toString()
    {
        $str="<tr><td>$this->category_id</td><td>$this->name</td></tr>";
        return $str;
    }
    
    
    //CRUD
    public function create($connection)
    {
        $category_id = $this->category_id;
        $name = $this->name;
        
        $sqlStmt ="INSERT INTO categories VALUES ('$category_id','$name')";
        $result = $connection -> exec($sqlStmt);
        return $result;
    }
    
    
    public function update($connection)
    {
        $category_id = $this->category_id;
        $name = $this->name;
        
        $sqlStmt = "UPDATE categories SET name = '$name' WHERE category_id = $category_id";
        $result = $connection->exec($sqlStmt);
        return $result;
    }
    
     
    public function delete($connection)
    {
        $category_id = $this -> category_id;
        $sqlStmt = "DELETE FROM categories WHERE category_id = $category_id";
        $result = $connection -> exec($sqlStmt);
        return $result;
    }    
}

?>