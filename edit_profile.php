<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $course = $_POST['course'];
    $phone_number = $_POST['phone_number'];
    $user_address = $_POST['user_address'];
    $profile_image = null;

    // Get the existing profile image path from the database
    try {
        $stmt = $connection->prepare("SELECT profile_image FROM users WHERE student_id = ?");
        $stmt->execute([$user_id]);
        $existingProfile = $stmt->fetchColumn();
    } catch (Exception $e) {
        error_log("Error fetching existing profile image: " . $e->getMessage());
        echo json_encode(['res' => 'error', 'msg' => 'Failed to fetch existing profile image.']);
        exit();
    }

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'profiles/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create folder if it doesn't exist
        }

        $file_name = basename($_FILES['profile_image']['name']);
        $target_file = $upload_dir . $file_name;

        // Check if the file already exists
        if (file_exists($target_file)) {
            $profile_image = $target_file; // Use the existing file path
        } else {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $profile_image = $target_file;

                // Delete the old profile image if it exists
                if ($existingProfile && file_exists($existingProfile)) {
                    unlink($existingProfile);
                }
            } else {
                error_log("Error uploading profile image.");
                echo json_encode(['res' => 'error', 'msg' => 'Failed to upload profile image.']);
                exit();
            }
        }
    } else {
        // If no new image is uploaded, keep the existing image path
        $profile_image = $existingProfile;
    }

    // Update query
    try {
        $query = "UPDATE users SET first_name = ?, last_name = ?, course = ?, phone_number = ?, user_address = ?, profile_image = ? WHERE student_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$first_name, $last_name, $course, $phone_number, $user_address, $profile_image, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['res' => 'success', 'msg' => 'Profile updated successfully.']);
        } else {
            echo json_encode(['res' => 'error', 'msg' => 'No changes were made to the profile.']);
        }
    } catch (Exception $e) {
        error_log("Error updating profile: " . $e->getMessage());
        echo json_encode(['res' => 'error', 'msg' => $e->getMessage()]);
    }
} else {
    echo json_encode(['res' => 'error', 'msg' => 'Invalid request method.']);
}
?>