<?php
// filepath: c:\xampp\htdocs\JavaScript\Road-to-javaScript\logout.php

    session_start(); // Start the session
    session_destroy(); // Destroy all session data

    // Respond with a JSON success message
    $response = array('res' => 'success', 'msg' => 'Logout successful');
    echo json_encode($response);
    exit();
?>