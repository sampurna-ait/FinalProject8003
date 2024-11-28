<?php
session_start();
include 'db.php';
$connection = getDbConnection();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  //redirects to login.php is user not logged in
    exit();
} else {
    echo "Logged in as user ID: " . $_SESSION['user_id'];
}

$user_id = $_SESSION['user_id'];

//delet the list/movie
if (isset($_GET['delete_favorite'])) {
    $movie_id = (int)$_GET['delete_favorite'];
    $delete_query = "DELETE FROM user_favorites WHERE user_id = $user_id AND movie_id = $movie_id";
    
    if (mysqli_query($connection, $delete_query)) {
        echo "<p>Movie removed from your favourites successfully!</p>";
    } else {
        echo "<p>Error removing movie: " . mysqli_error($connection) . "</p>";
    }
}


//get favourite list
$query = "SELECT movies.* FROM user_favorites 
          INNER JOIN movies ON user_favorites.movie_id = movies.id 
          WHERE user_favorites.user_id = $user_id";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favourite Movies</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   
    <link rel="stylesheet" href="movies.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="Navigation.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="filterbar.css?v=<?php echo time(); ?>">
</head>
<body>
    <nav class="main-navigation">
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="Movies.php">Movies</a></li>
            <li><a href="Tvshows.php">TV Shows</a></li>
            <li><a href="favourite.php" class="active">My Wishlist</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
        <div class="admin-access">
         <?php
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Display only the logout for logged-in users only
    echo '<a href="logout.php" class="logout-link" style="float: right;"><i class="fas fa-sign-out-alt"></i></a>';

}
?>
            <a href="admin_page.php" class="admin-link">Admin</a>
         </div>
    </nav>
    <main>
        <h1>My Favourite Movies</h1>
        <a href="Movies.php" class="back-button">Back to Movies</a>
        <div class="movie-grid">
        <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="movie-item">';
                    echo '<img src="' . $row['image'] . '" alt="' . $row['title'] . '">';
                    echo '<p class="movie-title">' . $row['title'] . '</p>';
                    echo '<p class="movie-year">Year: ' . $row['year'] . '</p>';
                    echo '<p class="movie-rating"><i class="fa fa-star"></i> ' . $row['ratings'] . '/10</p>';
                    echo '<a href="?delete_favorite=' . $row['id'] . '" class="delete-button">Remove</a>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-favorites-message">You have no favourites yet. <a href="Movies.php">Browse Movies</a></p>';
            }
        ?>
        </div>
    </main>
</body>
</html>
