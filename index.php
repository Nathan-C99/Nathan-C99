<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: signup.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Cemetery Management System</h1>
        <p>Please <a href="login.php">login</a> to access the dashboard or <a href="signup.php">sign up</a> if you don't have an account.</p>
    </div>
</body>
</html>
