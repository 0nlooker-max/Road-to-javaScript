<?php
header('Content-Type: application/json');

require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $assigned_students = $_POST['assigned_student']; // This will be an array
    $due_date = $_POST['due_date'];

    try {
        // Insert each student-task assignment into the database
        foreach ($assigned_students as $student_id) {
            $stmt = $connection->prepare("INSERT INTO tasks (task_title, discription, student_id, deadline, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$task_title, $task_description, $student_id, $due_date, 'Pending']);
        }

        echo json_encode(['res' => 'success', 'msg' => 'Task added successfully.']);
    } catch (Exception $e) {
        echo json_encode(['res' => 'error', 'msg' => 'Failed to add task: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['res' => 'error', 'msg' => 'Invalid request method.']);
}
?>