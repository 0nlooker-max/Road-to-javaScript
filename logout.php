<?php
// filepath: c:\xampp\htdocs\JavaScript\Road-to-javaScript\logout.php

session_start(); // Start the session
session_destroy(); // Destroy all session data

// Redirect to the login page with a logout message
header("Location: login.html?message=logged_out");
exit();
?>