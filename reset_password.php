<?php
session_start();
include 'db_connect.php'; // Include the database connection

// Initialize error and success variables
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset'])) {
    $token = $_POST['token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the token is valid and has not expired
        $sql = "SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Fetch the email associated with the token
                $stmt->bind_result($email);
                $stmt->fetch();
                
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the users table
                $sql = "UPDATE users SET password = ? WHERE email = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ss", $hashed_password, $email);
                    if ($stmt->execute()) {
                        // Delete the token after successful password reset
                        $sql = "DELETE FROM password_resets WHERE token = ?";
                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("s", $token);
                            $stmt->execute();
                        }

                        $success = "Your password has been successfully reset. You can now <a href='login.php'>login</a>.";
                    } else {
                        $error = "Failed to update the password.";
                    }
                } else {
                    $error = "Failed to prepare the SQL statement.";
                }
            } else {
                $error = "Invalid or expired token.";
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <!-- Display any success or error messages -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br>
            <button type="submit" name="reset">Reset Password</button>
        </form>
    </div>
</body>
</html>
