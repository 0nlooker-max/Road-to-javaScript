<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';

try {
    $studentId = $_POST['studentId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $userAddress = $_POST['userAddress'];
    $birthdate = $_POST['birthdate'];
    $profileImagePath = null;

    // Get the existing profile image path from the database
    $stmt = $connection->prepare("SELECT profile FROM prilimtable WHERE student_id = ?");
    $stmt->execute([$studentId]);
    $existingProfile = $stmt->fetchColumn();

    // Check if a new profile image is uploaded
    if (!empty($_FILES["editProfile"]["name"])) {
        $uploadDir = "profiles/"; // Folder where images will be stored
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create folder if it doesn't exist
        }

        $imageName = basename($_FILES["editProfile"]["name"]);
        $uploadFile = $uploadDir . $imageName;

        // Check if the file already exists in the folder
        if (file_exists($uploadFile)) {
            $profileImagePath = $uploadFile; // Use the existing file path
        } else {
            if (move_uploaded_file($_FILES["editProfile"]["tmp_name"], $uploadFile)) {
                $profileImagePath = $uploadFile; // Save this path in the database

                // Delete the old profile image if it exists
                if ($existingProfile && file_exists($existingProfile)) {
                    unlink($existingProfile);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Image upload failed."]);
                exit;
            }
        }
    } else {
        // If no new image is uploaded, keep the existing image path
        $profileImagePath = $existingProfile;
    }

    // Update query
    $stmt = $connection->prepare("UPDATE prilimtable SET first_name = ?, last_name = ?, email = ?, gender = ?, course = ?, user_address = ?, birthdate = ?, profile = ? WHERE student_id = ?");
    $stmt->execute([$firstName, $lastName, $email, $gender, $course, $userAddress, $birthdate, $profileImagePath, $studentId]);

    $response = array('res' => 'success', 'msg' => 'Student updated successfully');
    echo json_encode($response);
} catch (Exception $e) {
    $response = array('res' => 'error', 'msg' => $e->getMessage());
    echo json_encode($response);
}
?>