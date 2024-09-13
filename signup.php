<?php 
session_start(); // Start a session to manage user registration

include 'db_connect.php'; // Include the database connection

// Initialize error variable
$error = '';

// Check if the signup form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    // Check if POST data is set
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif ($password !== $confirm_password) {
            // Check if passwords match
            $error = "Passwords do not match.";
        } else {
            // Check if email already exists in the database
            $sql = "SELECT * FROM users WHERE email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error = "Email is already registered. Please use another email or login.";
                } else {
                    // Hash the password for security
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Prepare a SQL statement to insert user data into the database
                    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
                    if ($stmt2 = $conn->prepare($sql)) {
                        $stmt2->bind_param("ss", $email, $hashed_password); // Bind the input values to the query
                        if ($stmt2->execute()) {
                            // Redirect to the login page after successful signup
                            header("Location: login.php");
                            exit();
                        } else {
                            $error = "There was an error creating your account.";
                        }
                    } else {
                        $error = "Failed to prepare the SQL statement.";
                    }
                }

                $stmt->close();
            } else {
                $error = "Failed to prepare the SQL statement.";
            }
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        
        <!-- Display any error messages -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br>
            <button type="submit" name="signup">Sign Up</button>
        </form>

        <!-- Link to Login page -->
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
