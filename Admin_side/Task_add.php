<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $taskTitle = $_POST['task_title'];
        $taskDescription = $_POST['task_description'];
        $dueDate = $_POST['due_date'];
        $assignedStudents = $_POST['assigned_student']; // Array of student IDs

        // Insert task into the `tasks` table
        $stmt = $connection->prepare("INSERT INTO tasks (task_title, discription, deadline) VALUES (?, ?, ?)");
        $stmt->execute([$taskTitle, $taskDescription, $dueDate]);
        $taskId = $connection->lastInsertId();

        // Assign task to students
        $stmt = $connection->prepare("INSERT INTO task_assignment (task_id, student_id, status) VALUES (?, ?, 'Pending')");
        foreach ($assignedStudents as $studentId) {
            $stmt->execute([$taskId, $studentId]);
        }

        echo json_encode(['res' => 'success', 'msg' => 'Task added successfully']);
    } catch (Exception $e) {
        echo json_encode(['res' => 'error', 'msg' => 'Failed to add task: ' . $e->getMessage()]);
    }
}
?>