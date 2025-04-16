<?php
class User {
    private $user_id;
    private $email;
    private $name;
    private $password;
    
    function __construct($user_id = null, $email = null, $name = null, $password = null)
    {
        $this -> user_id = $user_id;
        $this -> email = $email;
        $this -> name = $name;
        $this -> password = $password;
    }
    
    public function getUserId()
    {
        return $this -> user_id;
    }
    
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    
    public function getEmail()
    {
        return $this -> email;
    }
    
    public function setEmail($email)
    {
        $this -> email = $email;
    }
    
    public function getName()
    {
        return $this -> name;
    }
    
    public function setName($name)
    {
        $this -> name = $name;
    }
    
    public function setPassword($password)
    {
        $this->password = md5($password);
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public static function getHeader()
    {
        $str = "<table border='1'>";
        $str .= "<tr><th>User ID</th><th>Name</th><th>Password</th></tr>";
        return $str;
        
    }
    public static function getFooter()
    {
        return"</table>";
    }
    
    public function __toString()
    {
        $str="<tr><td>$this->user_id</td><td>$this->name</td><td>$this->password</td></tr>";
        return $str;
    }
    
    //CRUD
    public function create($connection)
    {
        $user_id=$this->user_id;
        $email=$this->email;
        $name=$this->name;
        $password = $this->password;
        
        $sqlStmt ="INSERT INTO users VALUES ('$user_id', '$email', '$name', '$password')";
        $result = $connection -> exec($sqlStmt);
        return $result;
    }
    
    /* public function update($connection)
     {
     $teacher_id = $this->teacherId;
     $phone = $this->phone;
     $sqlStmt = "update teacher set phone = '$phone' where teacherId = $teacher_id";
     $result = $connection->exec($sqlStmt);
     return $result;
     } */
    
    public function delete($connection){
        $user_id = $this->user_id;
        $sqlStmt = "DELETE FROM users WHERE user_id = $user_id";
        $result = $connection -> exec($sqlStmt);
        return $result;
    }
    
    public function __call($method, $arg){
        
        if($method === "update"){
            $user_id = $this->user_id;
            $connection = $arg[0];
            $nbParam = count($arg);
            
            switch ($nbParam){
                case 1 :
                    $name = $this->name;
                    $sqlStmt = "UPDATE users SET name = '$name' WHERE user_id = $user_id";
                    break;
                case 2 :
                    $password = $this->password;
                    $sqlStmt = "UPDATE users SET password = '$password' WHERE user_id = $user_id";
                    break;
            }
            
            $result = $connection->exec($sqlStmt);
            return $result;
        }
    }
    
    public function getAllUsers($connection)
    {
        $counter = 0;
        $sqlStmt = "SELECT * FROM users";
            foreach ($connection -> query($sqlStmt) as $oneRec)
            {
                $user = new User();
                $user -> setUserId($oneRec["user_id"]);
                $user -> setEmail($oneRec["email"]);
                $user -> setName($oneRec["name"]);
                $user->setPasswordHash($oneRec["password"]);
                $tabUsers[$counter++] = $user;
            }
            return serialize($tabUsers);
        
    }
    
    public static function displayUsers($tabOfUser)
    {
        echo User :: getHeader();
        foreach ($tabOfUser as $oneUser)
            echo $oneUser;
            echo User :: getFooter();
    }
    
    public function getUserById($connection)
    {
        $user_id = $this->user_id;
        $sqlStmt = "SELECT * FROM users WHERE user_id=:p1";
        $prepare = $connection -> prepare($sqlStmt);
        $prepare -> bindValue (":p1", $user_id, PDO::PARAM_INT);
        $prepare -> execute();
        $result = $prepare -> fetchAll();
        $u = null;
        if (sizeOf($result)>0)
        {
            $u = new User();
            $u -> user_id = $result[0]["user_id"];
            $u -> name = $result[0]["name"];
            $u->setPasswordHash($result[0]["password"]);
        }
        return serialize($u);
    }
    
    public function getUserByEmail($connection)
    {
        $email = $this->email;
        $sqlStmt = "SELECT * FROM users WHERE email = :p1";
        $prepare = $connection->prepare($sqlStmt);
        $prepare->bindValue(":p1", $email, PDO::PARAM_STR);
        $prepare->execute();
        $result = $prepare->fetchAll();
        
        // Debug para verificar o resultado
        if (count($result) == 0) {
            echo "No user found with this email.<br/>";
        } else {
            echo "User found: <br/>";
            print_r($result);  // Exibe os dados encontrados
        }
        
        $u = null;
        if (count($result) > 0) {
            $u = new User();
            $u->user_id = $result[0]["user_id"];
            $u->email = $result[0]["email"];
            $u->name = $result[0]["name"];
            $u->setPasswordHash($result[0]["password"]);
        }
        return serialize($u);
    }
    
    public function setPasswordHash($hashedPassword)
    {
        $this->password = $hashedPassword;
    }
    
    public function changePassword($connection, $currentPassword, $newPassword) {
        $sql = "SELECT password FROM users WHERE user_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id', $this->user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return "User not found.";
        }
        
        // Verifica a senha atual com md5
        if (md5($currentPassword) !== $result['password']) {
            return "Current password is incorrect.";
        }
        
        // Criptografa a nova senha com md5
        $newHashedPassword = md5($newPassword);
        
        // Atualiza no banco
        $updateSql = "UPDATE users SET password = :newPassword WHERE user_id = :id";
        $updateStmt = $connection->prepare($updateSql);
        $updateStmt->bindParam(':newPassword', $newHashedPassword);
        $updateStmt->bindParam(':id', $this->user_id);
        
        if ($updateStmt->execute()) {
            return "Password updated successfully.";
        } else {
            return "Failed to update password.";
        }
    }
    
    
}
?>