<?php
// Start the session
session_start();

// Destroy all session data
session_unset(); 

// Destroy the session
session_destroy(); 

// Optionally, you can redirect the user to a homepage or login page after logging out
header("Location: login.php"); // or replace with your preferred redirect URL
exit();
?>