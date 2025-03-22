<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email and password are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the email exists in the database
        $stmt = $connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['user_password'])) {
                // Check if the account is verified
                if ($user['is_verified'] == 1) {
                    // Start a session and store user information
                    session_start();
                    $_SESSION['user_id'] = $user['student_id'];
                    $_SESSION['email'] = $user['email'];

                    // Respond with success
                    echo json_encode(["res" => "success", "msg" => "Login successful"]);
                } else {
                    // Account not verified
                    echo json_encode(["res" => "error", "msg" => "Your account is not verified."]);
                }
            } else {
                // Invalid password
                echo json_encode(["res" => "error", "msg" => "Invalid password."]);
            }
        } else {
            // Email not found
            echo json_encode(["res" => "error", "msg" => "Email not found."]);
        }
    } else {
        // Missing email or password
        echo json_encode(["res" => "error", "msg" => "Email and password are required."]);
    }
} else {
    // Invalid request method
    echo json_encode(["res" => "error", "msg" => "Invalid request method."]);
}
?>