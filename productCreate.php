<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbconnect.php';

try {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $userAddress = $_POST['userAddress'];
    $birthdate = $_POST['birthdate'];

    // Insert query
    $stmt = $connection->prepare("INSERT INTO prilimtable (first_name, last_name, email, gender, course, user_address, birthdate) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $gender, $course, $userAddress, $birthdate]);

    $response = array('res' => 'success', 'msg' => 'Student added successfully');
    echo json_encode($response);
} catch (Exception $e) {
    $response = array('res' => 'error', 'msg' => $e->getMessage());
    echo json_encode($response);
}
?>