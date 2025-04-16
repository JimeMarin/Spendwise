<?php

class Goal {
    private $goalId;
    private $name;
    private $targetAmount;
    private $currentAmount;
    private $deadline;
    private $status;
    
    function __construct($goalId = null, $name = null, $targetAmount = null, $currentAmount = null, $deadline = null) {
        $this->goalId = $goalId;
        $this->name = $name;
        $this->targetAmount = $targetAmount;
        $this->currentAmount = $currentAmount;
        $this->deadline = $deadline;
        $this->status = "in_progress";
    }
    
    /**
     * @return mixed
     */
    public function getGoalId()
    {
        return $this->goalId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTargetAmount()
    {
        return $this->targetAmount;
    }

    /**
     * @return mixed
     */
    public function getCurrentAmount()
    {
        return $this->currentAmount;
    }

    /**
     * @return mixed
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $goalId
     */
    public function setGoalId($goalId)
    {
        $this->goalId = $goalId;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $targetAmount
     */
    public function setTargetAmount($targetAmount)
    {
        $this->targetAmount = $targetAmount;
    }

    /**
     * @param mixed $currentAmount
     */
    public function setCurrentAmount($currentAmount)
    {
        $this->currentAmount = $currentAmount;
    }

    /**
     * @param mixed $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function __toString()
    {
        $progress = min(100, round(($this->getCurrentAmount() / $this->getTargetAmount()) * 100));
        
        // Determine color based on status
        switch ($this->getStatus()) {
            case 'completed':
                $color = 'green';
                break;
            case 'failed':
                $color = 'red';
                break;
            default:
                $color = 'goldenrod'; // yellow-ish for in progress
                break;
        }
        
        // Progress bar HTML
        $progressBar = "
        <div style='background-color: #ddd; border-radius: 5px; overflow: hidden; height: 20px; width: 100%;'>
            <div style='width: {$progress}%; background-color: {$color}; height: 100%; text-align: center; color: white; line-height: 20px;'>
                {$progress}%
            </div>
        </div>";
        
        $str = "<tr class='goal-row'>
                <td class='delete-cell'>
                    <div class='delete-wrapper'>
                        <form method='POST' action=''>
                            <button type='submit' onclick='toggleConfirm(this)' class='delete-btn' name='delete_goal' value='{$this->getGoalId()}'>Delete</button>
                        </form>
                    </div>
                </td>
                <td>{$this->getName()}</td>
                <td>{$this->getTargetAmount()}</td>
                <td>{$this->getCurrentAmount()}</td>
                <td>{$this->getDeadline()}</td>
                <td>" . ucwords(str_replace('_', ' ', $this->getStatus())) . "</td>
                <td>{$progressBar}</td>
                <td class='edit-cell'>
                    <div class='edit-wrapper'>
                        <form method='POST' action=''>
                            <input type='hidden' name='edit_goalId' value='{$this->getGoalId()}'>
                            <input type='hidden' name='edit_name' value='{$this->getName()}'>
                            <input type='hidden' name='edit_targetAmount' value='{$this->getTargetAmount()}'>
                            <input type='hidden' name='edit_currentAmount' value='{$this->getCurrentAmount()}'>
                            <input type='hidden' name='edit_deadline' value='{$this->getDeadline()}'>
                            <button type='submit' onclick='toggleConfirm(this)' class='edit-btn' name='open_edit_modal' value='{$this->getGoalId()}'>Edit</button>
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
                        <th>Name</th>
                        <th>Target</th>
                        <th>Current Amount</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th></th>
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
            $sqlStmt = "INSERT INTO goals (goal_id, name, target_amount, current_amount, deadline, status)
                    VALUES (:goal_id, :name, :target_amount, :current_amount, :deadline, :status)";
            
            
            $stmt = $connection->prepare($sqlStmt);
            
            $stmt->bindParam(':goal_id', $this->goalId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindParam(':target_amount', $this->targetAmount, PDO::PARAM_STR);
            $stmt->bindParam(':current_amount', $this->currentAmount, PDO::PARAM_STR);
            $stmt->bindParam(':deadline', $this->deadline, PDO::PARAM_STR);
            $stmt->bindParam(':status', $this->status, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function update($connection)
    {
        try {
            if (empty($this->name) || empty($this->targetAmount) || empty($this->currentAmount) || empty($this->deadline) || empty($this->status)) {
                throw new Exception("information is missing.");
            }
            $sqlStmt = "UPDATE goals
                        SET name = :name, target_amount = :target_amount, current_amount = :current_amount, deadline = :deadline, status = :status
                        WHERE goal_id = :goal_id";
            $stmt = $connection->prepare($sqlStmt); 
            $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindParam(':target_amount', $this->targetAmount, PDO::PARAM_STR);
            $stmt->bindParam(':current_amount', $this->currentAmount, PDO::PARAM_STR);
            $stmt->bindParam(':deadline', $this->deadline, PDO::PARAM_STR);
            $stmt->bindParam(':status', $this->status, PDO::PARAM_STR);
            $stmt->bindParam(':goal_id', $this->goalId, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    public function delete($connection)
    {
        try {
            $sqlStmt = "DELETE FROM goals WHERE goal_id = :goal_id";
            $stmt = $connection->prepare($sqlStmt);
            $stmt->bindParam(':goal_id', $this->goalId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function getAllGoals($connection)
    {
        try {
            $sqlStmt= "SELECT goal_id, name, target_amount, current_amount, deadline, status
                  FROM goals";
            
            $tabGoals = [];
            
            foreach($connection -> query ($sqlStmt) as $oneRec)
            {
                $goal = new Goal(
                    $oneRec["goal_id"],
                    $oneRec["name"],
                    $oneRec["target_amount"],
                    $oneRec["current_amount"],
                    $oneRec["deadline"],
                    $oneRec["status"]
                    );
                $goal->setStatus($oneRec["status"]);
                $goal->checkStatus(); // <-- check and update status if necessary
                
                if ($goal->getStatus() !== $oneRec["status"]) {
                    $goal->update($connection);
                }
                $tabGoals[] = $goal;
            }
            return serialize ($tabGoals);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public static function displayGoals ($tabOfGoals)
    {
        echo Goal::getHeader();
        if (is_array($tabOfGoals)) {
            foreach ($tabOfGoals as $oneGoal)
                echo $oneGoal;
        } else {
            echo "<tr><td colspan='4'>No goal found.</td></tr>";
        }
        echo Goal::getFooter();
    }
    
    public function checkStatus() {
        $today = date('Y-m-d');
        
        // If the goal is already completed, don't touch it
        if ($this->status === "completed") {
            return;
        }
        
        // If deadline passed and target not reached
        if ($this->deadline < $today && $this->currentAmount < $this->targetAmount) {
            $this->status = "failed";
        }
        
        // If goal is reached before or on deadline
        if ($this->currentAmount >= $this->targetAmount) {
            $this->status = "completed";
        }
    }
}