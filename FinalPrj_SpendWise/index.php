<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <!-- Logo no topo -->
    <div class="text-center mb-4">
        <img src="imgs/LogoSW.png" alt="Logo" width="400" height="200">
    </div>   
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'registered'): ?>
        <div class="alert alert-success text-center">
            Registration successful! Please log in.
        </div>
    <?php endif; ?>

    <!-- Formulário de login -->
    <h2 class="mb-4 text-center">Login</h2>
    
    <div class="d-flex justify-content-center">
        <div class="border p-4 rounded shadow" style="width: 100%; max-width: 500px;">
            <form action="goSearchUser.php" method="post">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <div class="mt-3 text-center">
                <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            </div>
        </div>
    </div>

</body>
</html>
