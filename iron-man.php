<?php
session_start();
include 'db.php'; 
$connection = getDbConnection();


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please log in first to submit a review.'); window.location.href = 'login.php';</script>";
        exit;
    }
    // Get the submitted data
    $movie_id = 81;  // ID for iron Man movie
    $user_id = $_SESSION['user_id'];  
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    
    // Insert the data directly into the database
    $sql = "INSERT INTO movie_reviews (movie_id, user_id, rating, review, created_at) VALUES ($movie_id, $user_id, '$rating', '$review', NOW())";
    mysqli_query($connection, $sql);
}

// Fetch reviews for the movie
$movie_id = 81; // Movie ID for Iron Man
$sql = "SELECT rating, review, created_at FROM movie_reviews WHERE movie_id = $movie_id ORDER BY created_at DESC";
$result = mysqli_query($connection, $sql);
$reviews = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iron Man - Marvel Movies</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="movies.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="NavMovies.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Product.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Navigation.css?v=<?php echo time(); ?>">
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

    <div class="content">
        <div class="col-md-6">
            <figure class="movie-poster">
                <div class="poster-wrapper">
                    <img src="posters/Movies/Ironman.jpg" alt="Iron Man Poster">
                </div>
            </figure>
        </div>
        <div class="col-md-6">
            <h2 class="movie-title">Iron Man</h2>
            <div class="movie-summary">
                <p>Iron Man is a 2008 American superhero film based on the Marvel Comics character of the same name. Produced by Marvel Studios and distributed by Paramount Pictures,[a] it is the first film in the Marvel Cinematic Universe (MCU). Directed by Jon Favreau from a screenplay by the writing teams of Mark Fergus and Hawk Ostby, and Art Marcum and Matt Holloway, the film stars Robert Downey Jr. as Tony Stark / Iron Man alongside Terrence Howard, Jeff Bridges, Gwyneth Paltrow, Leslie Bibb, and Shaun Toub. In the film, following his escape from captivity by a terrorist group, world-famous industrialist and master engineer Stark builds a mechanized suit of armor and becomes the superhero Iron Man</p>
            </div>
            <ul class="movie-meta">
                <li><strong>Director:</strong>Jon Favreau</li>
                <li><strong>Length:</strong> 126 min</li>
                <li><strong>Premiere:</strong> 2 May 2008 (USA)</li>
                <li><strong>Category:</strong> Phase 1</li>
            </ul>
            
              <!-- Rating and Review Form -->
              <div class="rating-input">
                <form method="POST" action="iron-man.php">
                    <label for="rating">Rate this movie:</label>
                    <select id="rating" name="rating" required>
                        <option value="0" selected>Select Rating</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
                        <?php endfor; ?>
                    </select>

                    <label for="review">Write a review:</label>
                    <textarea id="review" name="review" rows="4" placeholder="Share your thoughts about the movie" required></textarea>

                    <button type="submit">Submit Rating and Review</button>
                </form>
            </div>
            
            <div class="back-button">
               <a href="movies.php" class="back-btn">Back to Movies</a>
            </div>

            <!-- Display Rating and Review  -->
            <h3>Ratings and Reviews</h3>
            <?php if (count($reviews) > 0): ?>
                <ul class="reviews">
                    <?php foreach ($reviews as $review): ?>
                        <li>
                            <p><strong>Rating:</strong> <?php echo $review['rating']; ?> out of 5</p>
                            <p><strong>Review:</strong> <?php echo htmlspecialchars($review['review']); ?></p>
                            <p><small>Posted on: <?php echo date('F j, Y, g:i a', strtotime($review['created_at'])); ?></small></p>
                            <hr>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No reviews yet. Be the first to rate and review this movie!</p>
            <?php endif; ?>
        </div>
    </div>

    <footer class="site-footer">
            <div class="container">
                <div class="footer-sections">
                    <section class="footer-social">
                        
                        <ul class="social-media no-bullet">
                        <footer class="site-footer">
            <div class="container">
                <div class="footer-sections">
                    <section class="footer-social">
                        <h3>Social Media</h3>
                        <ul class="social-media no-bullet">
                            <li><a href="#" class="fa fa-facebook"></a></li>
                            <li><a href="#" class="fa fa-twitter"></a></li>
                            <li><a href="#" class="fa fa-google"></a></li>
                            <li><a href="#" class="fa fa-instagram"></a></li>
                          
                        </ul>
                    </section>
                </div>
                <p class="colophon">&copy; 2024 Marvel Verse All rights reserved.</p>
            </div>
        </footer>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/app.js"></script>
    <script src="script.js"></script>
</body>
</html>  