<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="UserPage.css">
</head>
<body>

<?php
session_start();
include 'db.php';

$connection = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id']) && isset($_POST['movie_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $movie_id = intval($_POST['movie_id']);

        // Check if the movie is already in favorites
        $checkQuery = "SELECT * FROM user_favorites WHERE user_id = $user_id AND movie_id = $movie_id";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Movie already in favorites
            echo '<div class="message message-warning">This movie is already in your favorites!</div>';
        } else {
            // Insert into the favorites table
            $query = "INSERT INTO user_favorites (user_id, movie_id) VALUES ($user_id, $movie_id)";
            $result = mysqli_query($connection, $query);

            if ($result) {
                // Movie added successfully
                echo '<div class="message message-success">Movie added to your favorites!</div>';
            } else {
                // Error
                echo '<div class="message message-error">An error occurred: ' . mysqli_error($connection) . '</div>';
            }
        }
    } else {
        // User not logged in
        echo '<div class="message message-error">Please log in to add movies to your favorites.</div>';
    }
}
?>

  <a href="Movies.php" class="back-button">Back to Movies</a>
</body>
</html>

