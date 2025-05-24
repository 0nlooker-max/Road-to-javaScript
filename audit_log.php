<?php
function log_audit($connection, $user_id, $action, $details = '') {
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $stmt = $connection->prepare("
        INSERT INTO audit_log (user_id, action, details, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $action, $details, $ip_address, $user_agent]);
}
?>