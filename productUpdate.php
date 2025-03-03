<?php
    header('Content-Type: application/json');

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include 'dbconnect.php';
    //taking the values from the form the POST Ajax request
    try {
        $studentId = $_POST['studentId'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $course = $_POST['course'];
        $userAddress = $_POST['userAddress'];
        $birthdate = $_POST['birthdate'];

        // Update query
        $stmt = $connection->prepare("UPDATE prilimtable SET first_name = ?, last_name = ?, email = ?, gender = ?, course = ?, user_address = ?, birthdate = ? WHERE student_id = ?");
        $stmt->execute([$firstName, $lastName, $email, $gender, $course, $userAddress, $birthdate, $studentId]);
        //giving the response to the ajax request
        $response = array('res' => 'success', 'msg' => 'Student updated successfully');
        echo json_encode($response);
    } catch (Exception $e) {
        $response = array('res' => 'error', 'msg' => $e->getMessage());
        echo json_encode($response);
    }
?>