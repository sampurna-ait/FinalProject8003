<?php
   // Starts the session 
   session_start();
   include 'db.php'; 
   $connection = getDbConnection();
   
   // get movies from the database
   $query = "SELECT * FROM movies";
   $result = mysqli_query($connection, $query);
   if (!$result) {
       die("Query failed: " . mysqli_error($connection));
   }
   
   $movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
   // get the selected phase 
   $selected_phase = isset($_GET['phase']) ? $_GET['phase'] : 'all';
   
   // valid phases
   $valid_phases = ['Phase 1', 'Phase 2', 'Phase 3', 'Phase 4', 'Phase 5', 'all'];
   
   // check selected phase
   if (!in_array($selected_phase, $valid_phases)) {
       $selected_phase = 'all';
   }
   
   ?>
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
      <link rel="stylesheet" href="movies.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="Navigation.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="filterbar.css?v=<?php echo time(); ?>">
   </head>
   <body>
      <nav class="main-navigation">
         <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="movies.php" class="active">Movies</a></li>
            <li><a href="Tvshows.php">TV Shows</a></li>
            <li><a href="favorite.php">My Wishlist</a></li>
            <li><a href="login.php">Login</a></li>
         </ul>
         <form action="search.php" method="post" class="search-bar">
            <input type="text" id="K_search" name="key_search" placeholder="Search movies..." required>
            <button type="submit" id="search" class="search-button"><i class="fa fa-search"></i></button>
         </form>
         <div id="div1"></div>
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
         <script>
            $(document).ready(function(){
                
                $("#form").click(function(event){
                    event.preventDefault();  
                    var keyword1 = $("#K_search").val();  
            
                    
                    if (keyword1 != "") {
                        $.ajax({
                            url: "search.php", 
                            method: "POST",  
                            data: { key_search: keyword1 },  
                            success: function(result) {
                                $("#div1").html(result);  
                            }
                        });
                    }
                });
            });
         </script>
      </nav>

     <div class="filter-container">
   <h2>Phase</h2>
   <div class="filter-buttons">
      <a href="Movies.php?phase=all" class="filter-button <?php echo ($selected_phase === 'all') ? 'active' : ''; ?>">All Movies</a>
      <a href="Movies.php?phase=Phase 1" class="filter-button <?php echo ($selected_phase === 'Phase 1') ? 'active' : ''; ?>">Phase 1</a>
      <a href="Movies.php?phase=Phase 2" class="filter-button <?php echo ($selected_phase === 'Phase 2') ? 'active' : ''; ?>">Phase 2</a>
      <a href="Movies.php?phase=Phase 3" class="filter-button <?php echo ($selected_phase === 'Phase 3') ? 'active' : ''; ?>">Phase 3</a>
      <a href="Movies.php?phase=Phase 4" class="filter-button <?php echo ($selected_phase === 'Phase 4') ? 'active' : ''; ?>">Phase 4</a>
      <a href="Movies.php?phase=Phase 5" class="filter-button <?php echo ($selected_phase === 'Phase 5') ? 'active' : ''; ?>">Phase 5</a>
   </div>
</div>


      <main>
         <h1>Marvel Movies</h1>
         <div class="movie-grid">
            <?php
               // loop through each movie in the array
               foreach ($movies as $movie) {
                   
                   // checks if the movie matches the selected phase or if all movies is selected
                   if ($selected_phase === 'all' || $movie['phase'] === $selected_phase) {
                       echo '<div class="movie-item" data-phase="' . $movie["phase"] . '">';
                       $movieTitle = strtolower(str_replace([' ', ':'], '-', $movie['title'])); 
                       
                       // link to the  detailed page of each movie
                       echo '<a href="' . $movieTitle . '.php">';
                       // shows movie posters
                       echo '<img src="' . $movie["image"] . '" alt="' . $movie["title"] . '">';
                       // movie information 
                       echo '<div class="movie-info">';
                       echo '<h2 class="movie-title">' . $movie["title"] . '</h2>';
                       echo '<p class="movie-year">' . $movie["year"] . '</p>';
               
                     // see if the rating is valid and display it with stars
                     if (!empty($movie["ratings"])) {
                       $rating = $movie["ratings"];
                       echo '<p class="movie-rating">';
                       
                       // display the rating
                       echo '<i class="fa fa-star"></i> ' . $rating . '/10';
                       echo '</p>';
                   } else {
                       echo '<p class="movie-rating">Rating: Not Available</p>';
                   }
               
                       echo '</div>';
                       echo '</a>';

                       echo '<form action="add_to_favorites.php" method="POST">';
                       echo '<input type="hidden" name="movie_id" value="' . $movie['id'] . '">';
                       echo '<button type="submit" class="favorite-button">Add to Favorites</button>';
                       echo '</form>';


                       echo '</div>';
                   }
               }
               ?>
         </div>
      </main>
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
   </body>
</html>