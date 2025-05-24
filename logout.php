<?php
// filepath: c:\xampp\htdocs\JavaScript\Road-to-javaScript\logout.php
require_once 'audit_log.php';
session_start(); // Start the session
session_destroy(); // Destroy all session data

// On logout
log_audit($connection, $user_id, 'logout', 'User logged out');

// Respond with a JSON success message
$response = array('res' => 'success', 'msg' => 'Logout successful');
echo json_encode($response);
exit();
?>