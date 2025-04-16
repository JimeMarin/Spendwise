<?php
require_once 'dbConfig.php';
require_once 'Topbar.php';
require_once 'heading.php';
require_once 'User.class.php';

if (!isset($_SESSION["ID"])) {
    header("Location: login.php"); 
    exit;
}

$user = new User();
$user->setUserId($_SESSION["ID"]);

$response = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    $response = $user->changePassword($connection, $currentPassword, $newPassword);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>

    <?php if ($response): ?>
        <p><?= htmlspecialchars($response) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="currentPassword">Current Password:</label><br>
        <input type="password" name="currentPassword" required><br><br>

        <label for="newPassword">New Password:</label><br>
        <input type="password" name="newPassword" required><br><br>

        <button type="submit">Change Password</button>
    </form>
</body>
</html>
