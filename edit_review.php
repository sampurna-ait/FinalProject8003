<?php
// Start session
session_start();
include 'db.php';
$connection = getDbConnection();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit();
}

// Get the review ID from the URL
if (isset($_GET['id'])) {
    $review_id = (int) $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // select previous reviews details from the database
    $query = "SELECT * FROM movie_reviews WHERE id = $review_id AND user_id = $user_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $review = mysqli_fetch_assoc($result);
    } else {
        echo "Review not found or you do not have permission to edit it.";
        exit();
    }
} else {
    echo "Invalid review ID.";
    exit();
}

//update the review
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_review'])) {
    $new_review = mysqli_real_escape_string($connection, $_POST['review']);
    $new_rating = (int) $_POST['rating'];

    // Update the review in the database
    $update_query = "UPDATE movie_reviews SET review = '$new_review', rating = $new_rating WHERE id = $review_id AND user_id = $user_id";

    if (mysqli_query($connection, $update_query)) {
        echo "<p>Review updated successfully!</p>";
        header("Location: admin_page.php");  //back to the admin page after update
        exit();
    } else {
        echo "<p>Error updating review: " . mysqli_error($connection) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="UserPage.css">
    <link rel="stylesheet" href="Navigation.css">
    <link rel="stylesheet" href="edit.css">
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

    <h1>Edit Review</h1>
    <form action="edit_review.php?id=<?php echo $review_id; ?>" method="POST">
        <label for="review">Review:</label>
        <textarea id="review" name="review" required><?php echo htmlspecialchars($review['review']); ?></textarea>

        <label for="rating">Rating (1-5):</label>
        <input type="number" id="rating" name="rating" min="1" max="5" value="<?php echo htmlspecialchars($review['rating']); ?>" required>

        <button type="submit" name="update_review">Update Review</button>
    </form>

    <a href="admin_page.php">Back to Admin Page</a>
</body>
</html>
