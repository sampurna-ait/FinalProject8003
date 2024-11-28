<?php
// Start session
session_start();

// Destroy session
session_unset();
session_destroy();


header('Location: index.php');
exit();
?>
