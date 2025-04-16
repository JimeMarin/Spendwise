<?php
class Budget {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    public function getBudgetData() {
        $sqlStmt = "SELECT c.NAME AS category_name, SUM(e.amount) AS value_perCat
                    FROM expenses e
                    INNER JOIN categories c ON e.category_id = c.category_id
                    GROUP BY c.category_id";
        
        $resultCategories = $this->connection->query($sqlStmt);
        
        $data = [];
        $total = 0;
        
        if ($resultCategories) {
            $results = $resultCategories->fetchAll(PDO::FETCH_ASSOC);
            
            $numRows = count($results);
            
            if ($numRows > 0) {
                foreach ($results as $row) {
                    $data[$row["category_name"]] = (float)$row["value_perCat"];
                    $total += (float)$row["value_perCat"];
                }
            } else {
                echo "No data found on the database.";
            }
        } else {
            echo "Error in SQL query: " . $this->connection->errorInfo()[2];
        }
        
        return ['data' => $data, 'total' => $total];
    }
    
    public function calculatePercentages($data, $total) {
        $percentages = [];
        foreach ($data as $category => $value) {
            $percentage = ($value / $total) * 100;
            $percentages[$category] = round($percentage, 2);
        }
        return $percentages;
    }
}
?>
