<?php
session_start();
include 'db_connect.php'; // Include the database connection

// Initialize error variable
$error = '';

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Check if POST data is set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare a SQL statement to check user credentials
        $sql = "SELECT id, username, password_hash, role FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            // Check if the username exists
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $password_hash, $role);
                $stmt->fetch();

                // Verify the password
                if (password_verify($password, $password_hash)) {
                    // Set session variables and redirect to the dashboard
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    $_SESSION['user_id'] = $id;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "No user found with that username.";
            }

            $stmt->close();
        } else {
            $error = "Failed to prepare the SQL statement.";
        }
    } else {
        $error = "Please fill in all required fields.";
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
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit" name="login">Login</button>
            <br>
            <!-- Forgot Password Link -->
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </form>

        <!-- Link to Sign Up page -->
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
