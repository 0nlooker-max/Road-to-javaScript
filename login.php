<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email and password are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
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
                        $_SESSION['role'] = $user['role']; // Store the user's role

                        // Respond based on the user's role
                        if ($user['role'] === 'admin') {
                            $response = array('res' => 'success', 'msg' => 'Login successful', 'role' => 'admin');
                        } else {
                            $response = array('res' => 'success', 'msg' => 'Login successful', 'role' => 'student');
                        }
                        echo json_encode($response);
                    } else {
                        // Account not verified
                        $response = array('res' => 'error', 'msg' => 'Your account is not verified.');
                        echo json_encode($response);
                    }
                } else {
                    // Invalid password
                    $response = array('res' => 'error', 'msg' => 'Invalid password.');
                    echo json_encode($response);
                }
            } else {
                // Email not found
                $response = array('res' => 'error', 'msg' => 'Email not found.');
                echo json_encode($response);
            }
        } catch (Exception $e) {
            // Handle any exceptions
            $response = array('res' => 'error', 'msg' => $e->getMessage());
            echo json_encode($response);
        }
    } else {
        // Missing email or password
        $response = array('res' => 'error', 'msg' => 'Email and password are required.');
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = array('res' => 'error', 'msg' => 'Invalid request method.');
    echo json_encode($response);
}
?>