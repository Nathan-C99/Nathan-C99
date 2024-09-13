<?php 
session_start(); // Start a session to manage user login

include 'db_connect.php'; // Include the database connection

// Check if the login form has been submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a SQL statement to fetch user data based on the email
    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email); // Bind the email input to the query
        $stmt->execute();
        $result = $stmt->get_result(); // Execute and get the result
        $user = $result->fetch_assoc(); // Fetch the user as an associative array

        if ($user) {
            // Verify the password using the password_hash from the registration
            if (password_verify($password, $user['password'])) {
                // Store user info in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['email'];

                // Redirect to the dashboard after successful login
                header("Location: dashboard.php");
                exit();
            } else {
                // If password is incorrect
                $error = "Invalid password.";
            }
        } else {
            // If no user is found with the provided email
            $error = "No user found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <!-- Display any error messages -->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit" name="login">Login</button>
        </form>

        <!-- Links to Sign Up and Forgot Password pages -->
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        <p><a href="forgot_password.php">Forgot Password?</a></p>
    </div>
</body>
</html>

