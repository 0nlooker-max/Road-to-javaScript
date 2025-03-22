<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';

try {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $userAddress = $_POST['userAddress'];
    $birthdate = $_POST['birthdate'];
    $profileImagePath = null;

    if (!empty($_FILES["profileImage"]["name"])) {
        $uploadDir = "profiles/"; // Folder where images will be stored
        if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create folder if it doesn&#39;t exist
        }
        
        $imageName = basename($_FILES["profileImage"]["name"]);
        $uploadFile = $uploadDir . $imageName;
        
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $uploadFile)) {
        $profileImagePath = $uploadFile; // Save this path in the database
        } else {
        
        echo json_encode(["status" => "error", "message" => "Image upload failed."]);
        exit;
        }
        }
    // Insert query
    $stmt = $connection->prepare("INSERT INTO prilimtable (first_name, last_name, email, gender, course, user_address, birthdate, profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $gender, $course, $userAddress, $birthdate, $profileImagePath]);

    $response = array('res' => 'success', 'msg' => 'Student added successfully');
    echo json_encode(value: $response);
} catch (Exception $e) {
    $response = array('res' => 'error', 'msg' => $e->getMessage());
    echo json_encode($response);
}
?>