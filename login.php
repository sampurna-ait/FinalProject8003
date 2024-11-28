<?php
session_start();


// check to see if the user is already loged in
if (isset($_SESSION['user_id'])) {
    echo "<script>
        alert('You are already logged in!');
        window.location.href = 'index.php'; // Redirect to home page
    </script>";
    exit; 
}
// include the database connection
require_once 'db.php';

$error_message = '';
$conn = getDbConnection();

// processes login form using post methods
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // user table from the database 
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // verifies password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email; 
            $_SESSION['show_welcome'] = true; 
            header('Location: index.php'); // redirect to home page
            exit; 
        } else {
            $error_message = "Invalid password."; // error for incorrect password
        }
    } else {
        $error_message = "No user found with this email."; 
    }
}

// closesr the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marvel Verse - Login</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css" />
</head>
<body>

<button type="button" class="form-button cancel-button" id="cancel-btn" onclick="location.href='index.php';">Cancel</button>



</nav>
    <div class="container">
        <div class="logo">
            <h1>Marvel Verse</h1>
        </div>
        <div class="login-form">
            <h3>Welcome Back!</h3>
            
            <!-- displays error message  -->
            <?php if (!empty($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>

            <p class="create-account-text">
            Or <a href="register.php">Register</a>
        </p>

        </div>
    </div>

</body>
</html>
