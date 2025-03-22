<?php
require 'connection.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Update the user where the verification code matches
    $stmt = $connection->prepare("UPDATE users SET verification_code = NULL, is_verified = 1 WHERE verification_code = :code");
    $stmt->bindParam(':code', $code);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        // Verification successful
        echo "Your account has been verified!";
        header("Location: login.html?message=verified");
        exit();
    } else {
        // Invalid or expired code
        echo "Invalid or expired verification code.";
    }
} else {
    echo "No verification code provided.";
}
?>