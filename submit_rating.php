<?php
session_start();
include 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = getDbConnection();

    // Get form data
    $movie_id = intval($_POST['movies_id']);
    $rating = intval($_POST['rating']);
    $review = mysqli_real_escape_string($connection, trim($_POST['review']));
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

    // Validate input
    if (!$user_id) {
        die('Error: You must be logged in to submit a rating and review.');
    }

    if ($rating < 1 || $rating > 5) {
        die('Error: Invalid rating. Please provide a rating between 1 and 5.');
    }

    // Construct the SQL query
    $query = "INSERT INTO movie_reviews (movie_id, user_id, rating, review) 
              VALUES ($movie_id, $user_id, $rating, '$review')";

    // Execute the query
    if (mysqli_query($connection, $query)) {
        echo "Thank you for rating and reviewing the movie!";
    } else {
        echo "Error: Could not save your rating. Please try again.";
    }

    // Close the connection
    mysqli_close($connection);
}
?>
