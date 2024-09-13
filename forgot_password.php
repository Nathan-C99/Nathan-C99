<?php
session_start();
include 'db_connect.php'; // Include the database connection

// Initialize error and success variables
$error = '';
$success = '';

// Check if the request form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if the email exists in the database
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                // Generate a unique token
                $token = bin2hex(random_bytes(50));

                // Store the token in the database with an expiration time
                $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
                if ($stmt2 = $conn->prepare($sql)) {
                    // Bind only two parameters (email and token) since there are only two placeholders
                    $stmt2->bind_param("ss", $email, $token);
                    $stmt2->execute();

                    // Check if the query was successful
                    if ($stmt2->affected_rows > 0) {
                        // Send the password reset email
                        $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;
                        $subject = "Password Reset Request";
                        $message = "To reset your password, please visit the following link: " . $reset_link;
                        $headers = "From: no-reply@yourdomain.com";

                        if (mail($email, $subject, $message, $headers)) {
                            $success = "A password reset link has been sent to your email.";
                        } else {
                            $error = "Failed to send password reset email.";
                        }
                    } else {
                        $error = "Failed to insert reset token into database.";
                    }
                } else {
                    $error = "Failed to prepare the SQL statement.";
                }
            } else {
                $error = "No account found with that email.";
            }
            $stmt->close();
        } else {
            $error = "Failed to prepare the SQL statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>

        <!-- Display any success or error messages -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <button type="submit" name="submit">Send Reset Link</button>
        </form>

        <!-- Link to Login page -->
        <p>Remembered your password? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
