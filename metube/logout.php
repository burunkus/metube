<?php
session_start();
define("BASE_PATH", "");
?>

<?php
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy();

header("Location:index.php");
?>
