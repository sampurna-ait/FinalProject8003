<?php
   // Start the session if needed
   session_start();
   include 'db.php';
   $connection = getDbConnection();


  if (isset($_SESSION['user_id']) && isset($_SESSION['show_welcome'])) {
      // Prepare the welcome message for logged-in users
      $welcomeMessage = "Welcome, " . $_SESSION['email'] . "!";
      unset($_SESSION['show_welcome']); // Clear the flag to show the message only once
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Marvel Verse</title>
      <!-- Loading fonts and icons -->
      <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css" />
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="movies.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="Navigation.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="filterbar.css?v=<?php echo time(); ?>">
      <script>
    // Show the welcome message if it exists
    document.addEventListener("DOMContentLoaded", function () {
        <?php if ($welcomeMessage): ?>
            alert("<?php echo $welcomeMessage; ?>");
        <?php endif; ?>
    });
</script>
      <script src="indexc.js"></script>
   </head>
   <body>
      <nav class="main-navigation">
         <ul class="menu">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="Movies.php">Movies</a></li>
            <li><a href="Tvshows.php">TV Shows</a></li>
            <li><a href="favorite.php">My Wishlist</a></li>
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
      </header>
      <main class="main-content">
         <div class="carousel" data-flickity='{ "autoPlay": true }'>
            <div class="carousel-cell"><img src="posters/1.jpg" alt="Plot Detail 1"></div>
            <div class="carousel-cell"><img src="posters/3.jpg" alt="Plot Detail 3"></div>
            <div class="carousel-cell"><img src="posters/4.jfif" alt="Plot Detail 3"></div>
            <div class="carousel-cell"><img src="posters/5.jpg" alt="Plot Detail 3"></div>
            <div class="carousel-cell"><img src="posters/6.jpg" alt="Plot Detail 3"></div>
            <div class="carousel-cell"><img src="posters/7.jpg" alt="Plot Detail 3"></div>
            <div class="carousel-cell"><img src="posters/8.jpg" alt="Plot Detail 3"></div>
         </div>

    
         <div id="promoSidebar" class="promo-sidebar">
        

             <h2>Exclusive Promotions</h2>
            <ul class="promo-content">
            <?php
        $promoQuery = "SELECT * FROM promotions";
        $promoResult = mysqli_query($connection, $promoQuery);

        if ($promoResult && mysqli_num_rows($promoResult) > 0) {
            while ($promoRow = mysqli_fetch_assoc($promoResult)) {
                $promoTitle = $promoRow['title'];
                $promoUrl = $promoRow['url'];
                $promoDescription = $promoRow['description'];
                $promoImage = $promoRow['image'];

                echo "<li>
                        <a href='$promoUrl' target='_blank' title='$promoDescription'>
                            <img src='$promoImage' alt='$promoTitle' style='width: 100px; height: auto; margin-right: 10px;'>
                            $promoTitle
                        </a>
                     </li>";
            }
        } else {
            echo "<li>No promotions available at the moment.</li>";
        }
        ?>
        <h2>Some News From Marvel</h2>
           <li><a href="https://www.eventcinemas.com.au/Movie/Wicked" target="_blank">Click Here to Book Ticket For Wicked 2024</a></li>
<li><a href="https://www.disneyplus.com/en-au/movies/deadpool-wolverine/4TQTHo9Qto2m" target="_blank">Deadpool and Wolverine Streaming in Disney</a></li>
<li><a href="https://www.marvel.com/movies/captain-america-brave-new-world" target="_blank">Captain America Brave New World in theatre on Feb 14, 2025</a></li>
<li><a href="https://www.youtube.com/results?search_query=*+in+thunderbolt" target="_blank">Breakdown of * in Thunderbolts</a></li>
<li><a href="https://www.boxofficemojo.com/title/tt6263850/" target="_blank">Marvel cashed in $1.33 Billion from Deadpool</a></li>

           </ul>
           </div>
    

         <?php
            // random movies suggested from the database
            $query = "SELECT * FROM movies ORDER BY RAND() LIMIT 4"; 
            $result = mysqli_query($connection, $query);
            
            if (!$result) {
                die("Query failed: " . mysqli_error($connection));
            }
            
            // Check if there are any results
            if (mysqli_num_rows($result) > 0) {
                echo '<section>';
                echo '<h2>Suggestions</h2>';
                echo '<div class="movie-grid">';
            
                //loops and display the movies
                while ($row = mysqli_fetch_assoc($result)) {
                    $title = $row['title'];
                    $year = $row['year']; 
                    $ratings = $row['ratings'];
                    $poster = isset($row['image']) ? $row['image'] : 'posters/Movies/default.jpg'; 
                    $link = isset($row['movie_link']) ? $row['movie_link'] : '#'; 
            
                    
                    $stars = '<i class="fa fa-star"></i> ' . $ratings . '/10'; // display the star and rating score
            
                    echo '<div class="movie-item">';
                    echo '<a href="' . $link . '"><img src="' . $poster . '" alt="' . $title . '"></a>';
                    echo '<p class="movie-title">' . $title . '</p>';
                    echo '<p class="movie-year">Year: ' . $year . '</p>'; // display year
                    echo '<p class="movie-rating">' . $stars . '</p>'; // display stars and rating score
                    echo '</div>';
                }
            
                echo '</div>'; 
                echo '</section>'; 
            } else {
                echo '<p>No movies or shows found.</p>';
            }
            ?>
         <section>
            <h2>Now Playing</h2>
            <div class="movie-grid">
               <div class="movie-item">
                  <a href="#"><img src="posters/Now Playing/agatha.jpg" alt="Now Playing 1"></a>
                  <p class="movie-title">Agatha All Along </p>
               </div>
               <div class="movie-item">
                  <a href="#"><img src="posters/Now Playing/deadpoolandwolverine.jpg" alt="Now Playing 2"></a>
                  <p class="movie-title">Deadpool and Wolverine</p>
               </div>
               <div class="movie-item">
                  <a href="#"><img src="posters/Now Playing/xmen.jpg" alt="Now Playing 3"></a>
                  <p class="movie-title">Xmen 97</p>
               </div>
               <div class="movie-item">
                  <a href="#"><img src="posters/Now Playing/thelastdance.jpg" alt="Now Playing 4"></a>
                  <p class="movie-title">Venom: The Last Dance</p>
               </div>
            </div>
         </section>
         <section>
            <h2>Upcoming</h2>
            <div class="movie-grid">
               <div class="movie-item">
                  <a href="#"><img src="posters/Upcoming/avengersdoomsday.jpg" alt="Upcoming 1"></a>
                  <p class="movie-title">Avengers Doomsday - 26 June 2026</p>
               </div>
               <div class="movie-item">
                  <a href="#"><img src="posters/Upcoming/avengerssecretwars.jpg" alt="Upcoming 2"></a>
                  <p class="movie-title">Avengers Secret War - 07 May 2027</p>
               </div>
               <div class="movie-item">
                  <a href="#"><img src="posters/Upcoming/captainamericabravenewworld.jpg" alt="Upcoming 3"></a>
                  <p class="movie-title">Captain America - 05 Feburary 2025</p>
               </div>
               <div class="movie-item">
                  <a href="#"><img src="posters/Upcoming/thefantasticfourfirststep.jpg" alt="Upcoming 4"></a>
                  <p class="movie-title">Fantastic Four First Step 25 July 2025</p>
               </div>
            </div>
         </section>
         <h2>Top IMDb</h2>
         <div class="movie-grid">
            <div class="movie-item">
               <a href="#"><img src="posters/Top IMBD/blackpanther.jpg" alt="Top IMDb 1"></a>
               <p class="movie-title">Black Panther </p>
               <p class="rotten-tomato-score">Rotten Tomatoes: 96%</p>
            </div>
            <div class="movie-item">
               <a href="#"><img src="posters/Top IMBD/captainamericacivilwar.jpg" alt="Top IMDb 2"></a>
               <p class="movie-title">Captain America Civil War</p>
               <p class="rotten-tomato-score">Rotten Tomatoes: 91%</p>
            </div>
            <div class="movie-item">
               <a href="#"><img src="posters/Top IMBD/avengersendgame.jpg" alt="Top IMDb 3"></a>
               <p class="movie-title">Avengers Endgame</p>
               <p class="rotten-tomato-score">Rotten Tomatoes: 94%</p>
            </div>
            <div class="movie-item">
               <a href="#"><img src="posters/Top IMBD/spider-mannowayhome.jpg" alt="Top IMDb 4"></a>
               <p class="movie-title">Spider-Man: No Way Home</p>
               <p class="rotten-tomato-score">Rotten Tomatoes: 93%</p>
            </div>
         </div>
         </section>
         </div>
         </div>
      </main>
      <footer class="site-footer">
      <div class="container">
      <div class="footer-sections">
         <section class="footer-social">
         <ul class="social-media no-bullet">
         <footer class="site-footer">
            <div class="container">
               <div class="footer-sections">
                  <section class="footer-social">
                     <h2>Social Media</h2>
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
      </div>
      <script src="js/jquery-1.11.1.min.js"></scrip>
         <script src="js/plugins.js">
      </script>
      <script src="js/app.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.pkgd.min.js"></script>
      <script src="script.js"></script>
    
   </body>
   </body>
</html>

