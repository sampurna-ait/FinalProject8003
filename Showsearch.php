<?php
// Start the session
session_start();

// Include the database connection file
include 'db.php';
$connection = getDbConnection();

// Check if the search term is provided
if (isset($_POST['key_search']) && !empty($_POST['key_search'])) {
    $searchTerm = mysqli_real_escape_string($connection, $_POST['key_search']);
    
    // Query to search for shows
    $query = "SELECT * FROM shows WHERE title LIKE '%$searchTerm%' OR keyphrases LIKE '%$searchTerm%'";
    $result = mysqli_query($connection, $query);

    // Check for errors
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    $shows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Check if there are results
    if (empty($shows)) {
        echo '<p>No shows found matching your search.</p>';
    } else {
        foreach ($shows as $show) {
            echo '<div class="show-item">';
            echo '<img src="' . $show["image"] . '" alt="' . htmlspecialchars($show["title"], ENT_QUOTES) . '">';
            echo '<h2>' . htmlspecialchars($show["title"], ENT_QUOTES) . '</h2>';
            echo '<p>' . htmlspecialchars($show["year"], ENT_QUOTES) . '</p>';
            echo '<p>Category: ' . htmlspecialchars($show["category"], ENT_QUOTES) . '</p>';
            echo '</div>';
        }
    }
} else {
    echo '<p>Please enter a search term.</p>';
}

?>
<div style="text-align: center; margin-top: 20px;">
    <a href="Tvshows.php" class="btn btn-primary">Back to Tv Shows</a>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marvel Movies</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom styles -->
    <link rel="stylesheet" href="showresult.css?v=<?php echo time(); ?>">

  
</head>