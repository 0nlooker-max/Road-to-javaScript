<?php
require_once 'connection.php';
require_once '../audit_log.php';

session_start(); // Start the session

$user_id = $_SESSION['user_id'] ?? null; // Get user ID before destroying session

// Audit log: logout
log_audit($connection, $user_id, 'logout', 'User logged out');

session_destroy(); // Destroy all session data

// Respond with a JSON success message
$response = array('res' => 'success', 'msg' => 'Logout successful');
echo json_encode($response);
exit();
