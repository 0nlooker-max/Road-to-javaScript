<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['user_password'])) {
            // Check if the account is verified
            if ($user['is_verified'] == 1) {
                echo json_encode(["status" => "success", "message" => "Login successful"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Account not verified"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email not found"]);
    }
}





?>