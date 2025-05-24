<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'connection.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    try {
        // Fetch task details
        $stmt = $connection->prepare("SELECT task_title, discription AS description, deadline FROM tasks WHERE task_id = ?");
        $stmt->execute([$task_id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if task exists
        if (!$task) {
            echo json_encode(['error' => 'Task not found']);
            exit;
        }

        // Fetch pending assignments
        $stmt = $connection->prepare("
            SELECT CONCAT(u.first_name, ' ', u.last_name) AS name
            FROM task_assignment ta
            JOIN users u ON ta.student_id = u.student_id
            WHERE ta.task_id = ? AND ta.status = 'Pending'
        ");
        $stmt->execute([$task_id]);
        $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch submitted assignments
        $stmt = $connection->prepare("
            SELECT 
                ta.assignment_id, 
                CONCAT(u.first_name, ' ', u.last_name) AS name, 
                ta.attach_file, 
                ta.attach_link
            FROM task_assignment ta
            JOIN users u ON ta.student_id = u.student_id
            WHERE ta.task_id = ? AND ta.status = 'Submitted'
        ");
        $stmt->execute([$task_id]);
        $submitted = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if file exists for each submission
        foreach ($submitted as &$sub) {
            if (!empty($sub['attach_file']) && $sub['attach_file'] !== 'NULL') {
                $filePath = dirname(__DIR__) . '/' . ltrim($sub['attach_file'], '/');
                if (!file_exists($filePath)) {
                    $sub['attach_file'] = ''; // File does not exist, clear the link
                }
            }
        }
        unset($sub); // break reference

        // Fetch completed assignments
        $stmt = $connection->prepare("
            SELECT 
                ta.assignment_id, 
                CONCAT(u.first_name, ' ', u.last_name) AS name, 
                ta.date_submitted, 
                t.task_title
            FROM task_assignment ta
            JOIN users u ON ta.student_id = u.student_id
            JOIN tasks t ON ta.task_id = t.task_id
            WHERE ta.task_id = ? AND ta.status = 'Completed'
        ");
        $stmt->execute([$task_id]);
        $completed = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all assigned students (for editing)
        $stmt = $connection->prepare("
            SELECT u.student_id, CONCAT(u.first_name, ' ', u.last_name) AS name, u.first_name, u.last_name
            FROM task_assignment ta
            JOIN users u ON ta.student_id = u.student_id
            WHERE ta.task_id = ?
        ");
        $stmt->execute([$task_id]);
        $assigned_students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output all data as JSON
        echo json_encode([
            'task_title' => $task['task_title'],
            'description' => $task['description'],
            'deadline' => $task['deadline'],
            'pending' => $pending,
            'submitted' => $submitted,
            'completed' => $completed,
            'assigned_students' => $assigned_students // for edit modal
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to fetch task details: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Task ID not provided']);
}