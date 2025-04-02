<?php
require 'connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'profiles/';
        $file_name = basename($_FILES['profile_image']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $profile_image = $target_file;
        } else {
            echo json_encode(['res' => 'error', 'msg' => 'Failed to upload profile image.']);
            exit();
        }
    } else {
        $profile_image = null; // No new image uploaded
    }

    try {
        $query = "UPDATE users SET first_name = :first_name, last_name = :last_name";
        if ($profile_image) {
            $query .= ", profile_image = :profile_image";
        }
        $query .= " WHERE student_id = :user_id";

        $stmt = $connection->prepare($query);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        if ($profile_image) {
            $stmt->bindParam(':profile_image', $profile_image);
        }
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            echo json_encode(['res' => 'success', 'msg' => 'Profile updated successfully.']);
        } else {
            echo json_encode(['res' => 'error', 'msg' => 'Failed to update profile.']);
        }
    } catch (Exception $e) {
        echo json_encode(['res' => 'error', 'msg' => $e->getMessage()]);
    }
} else {
    echo json_encode(['res' => 'error', 'msg' => 'Invalid request method.']);
}
?>