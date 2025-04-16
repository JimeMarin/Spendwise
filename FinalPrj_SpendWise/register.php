<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="text-center mb-4">
        <img src="imgs/LogoSW.png" alt="Logo" width="200" height="100">
    </div>

    <h2 class="mb-4 text-center">Register New User</h2>

    <?php
    require_once 'dbConfig.php';
    require_once 'User.class.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        if (!empty($name) && !empty($email) && !empty($password)) {
            try {
                $connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

                $newUser = new User();
                $newUser->setName($name);
                $newUser->setEmail($email);
                $newUser->setPassword($password);

                $result = $newUser->create($connection);

                if ($result) {
                    header("Location: index.php?msg=registered");
                    exit;
                } else {
                    echo "<div class='alert alert-danger'>Failed to register user.</div>";
                }

            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Connection failed: " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Please fill in all fields.</div>";
        }
    }
    ?>

    <!-- Formulário de registro com bordas -->
    <div class="d-flex justify-content-center">
        <div class="border p-4 rounded shadow" style="width: 100%; max-width: 500px;">
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address:</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
    
</body>
</html>
