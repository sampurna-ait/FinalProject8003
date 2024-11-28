<?php
// Start session
session_start();
include 'db.php';
$connection = getDbConnection();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  //redirectes to login.php for sign in
    exit();
}

$user_id = $_SESSION['user_id'];
 $email = isset($_SESSION['email']) ? $_SESSION['email'] : "Not available";

echo "Welcome, user: " . $user_id . " and Email: " . $email;

// get the user's reviews
$query_reviews = "SELECT mr.*, m.title AS movie_title 
                  FROM movie_reviews mr 
                  JOIN movies m ON mr.movie_id = m.id 
                  WHERE mr.user_id = $user_id";
$result_reviews = mysqli_query($connection, $query_reviews);

if ($result_reviews === false) {
    echo "Error fetching reviews: " . mysqli_error($connection);
}

// Handle updating account details 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_account'])) {
    $new_email = mysqli_real_escape_string($connection, $_POST['email']);
    $new_password = mysqli_real_escape_string($connection, $_POST['password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_query = "UPDATE user SET email='$new_email', password='$hashed_password' WHERE id = $user_id";

    if (mysqli_query($connection, $update_query)) {
        $_SESSION['email'] = $new_email; // Update session variable
        echo "<p>Account details updated successfully!</p>";
    } else {
        echo "<p>Error updating account details: " . mysqli_error($connection) . "</p>";
    }
}

// Handle review deletion
if (isset($_GET['delete_review'])) {
    $review_id = (int) $_GET['delete_review'];
    $delete_query = "DELETE FROM movie_reviews WHERE id = $review_id AND user_id = $user_id";

    if (mysqli_query($connection, $delete_query)) {
        echo "<p>Review deleted successfully!</p>";
        header("Location: admin_page.php");
        exit();
    } else {
        echo "<p>Error deleting review: " . mysqli_error($connection) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="UserPage.css">
    <link rel="stylesheet" href="Navigation.css">
    <link rel="stylesheet" href="back.css">
</head>
<body>
<nav class="main-navigation">
         <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="movies.php" class="active">Movies</a></li>
            <li><a href="Tvshows.php">TV Shows</a></li>
            <li><a href="login.php">Login</a></li>
         </ul>
      </nav>
    <h1>Welcome</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="index.php" class="back-btn">Back to Home</a>

    <section>
        <h2>Your Reviews</h2>
        <table>
            <thead>
                <tr>
                    <th>Movie Title</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_reviews && mysqli_num_rows($result_reviews) > 0) {
                    while ($review = mysqli_fetch_assoc($result_reviews)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($review['movie_title']) . "</td>";
                        echo "<td>" . htmlspecialchars($review['review']) . "</td>";
                        echo "<td>" . htmlspecialchars($review['rating']) . "</td>";
                        echo "<td>
                                <a href='?delete_review=" . $review['id'] . "'>Delete</a> | 
                                <a href='edit_review.php?id=" . $review['id'] . "'>Edit</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No reviews found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Update Account Details</h2>
        <form action="admin_page.php" method="POST">
            <label for="email">New Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter new email" required>
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">

            <button type="submit" name="update_account">Update Account</button>
        </form>
    </section>
</body>
</html>
