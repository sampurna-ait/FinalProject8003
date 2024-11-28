<?php
session_start();

include 'db.php';
$conn = getDbConnection();

// Check connection
if ($conn) {
    echo "Connection successful!";
} else {
    echo "Connection failed! Error: " . mysqli_connect_error(); // displays MySQL connection error
}

if (isset($_POST['key_search'])) {
    $key_search = mysqli_real_escape_string($conn, $_POST['key_search']);
    echo "Sanitized input: " . $key_search . "<br>";

    // Search for the keyword
    $sql = "SELECT * FROM movies WHERE keyphrase LIKE '%$key_search%'";  
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results:</h2>";
        echo '<div class="row">';
        
        while ($row = mysqli_fetch_assoc($result)) {
            $movie_id = $row['id'];
            $title = $row['title'];
            $year = $row['year'];
            $phase = $row['phase'];
            $category = $row['category'];
            $image = $row['image'];

            //URl  title for the movie
            $moviePage = strtolower(str_replace([' ', ':'], '-', $title)) . '.php';

            echo '<div class="col-sm-6 col-sm-3">';
            echo '<div class="panel panel-default">';
            echo '<div class="panel-heading">';
            echo '<a href="' . $moviePage . '">' . $title . ' (' . $year . ')</a>';
            echo '</div>';
            echo '<div class="panel-body">';
            echo '<p><strong>Phase:</strong> ' . $phase . '</p>';
            echo '<p><strong>Category:</strong> ' . $category . '</p>';
            echo '<a href="' . $moviePage . '">';
            echo '<img src="' . $image . '" width="300" height="250" alt="Movie Poster">';
            echo '</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
    } else {
        echo "<p>No results found for your search.</p>";
    }
}
?>  

<div style="text-align: center; margin-top: 20px;">
    <a href="Movies.php" class="btn btn-primary">Back to Movies</a>
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
    <link rel="stylesheet" href="Movieresult.css?v=<?php echo time(); ?>">
</head>
<body>
</body>
</html>
