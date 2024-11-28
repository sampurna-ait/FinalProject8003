<?php
session_start();

// include the database connection
include 'db.php';


$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // validate user inputs
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // hashing the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $conn = getDbConnection();

        // checks if the email is  already in use 
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Email already exists.";
        } else {

            // inserts the new user into the database table named users
            $insert_sql = "INSERT INTO user (email, password) VALUES ('$email', '$hashed_password')";
            if (mysqli_query($conn, $insert_sql)) {
                $_SESSION['user'] = mysqli_insert_id($conn); // stores user ID in session
                $_SESSION['registration_success'] = "Registration successful! Welcome to Marvel Verse."; // Set notification message
                header('Location: index.php');
                exit;
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }

        // closes the database connection
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marvel Verse - Register</title>
    <link rel="stylesheet" href="admin.css">
</head>

<button type="button" class="form-button cancel-button" id="cancel-btn" onclick="location.href='index.php';">Cancel</button>

<body>
    <div class="container">
        <div class="logo">
            <h1>Marvel Verse</h1>
        </div>
        <div class="login-form">
            <h2>Create an Account</h2>
            
            <!-- Display error message -->
            <?php if (!empty($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
