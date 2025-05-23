<?php
require 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['task_id'], $data['task_title'], $data['task_description'], $data['task_deadline'], $data['assigned_students'])) {
    $task_id = $data['task_id'];
    $task_title = $data['task_title'];
    $task_description = $data['task_description'];
    $task_deadline = $data['task_deadline'];
    $assigned_students = $data['assigned_students'];

    try {
        // Update task details
        $stmt = $connection->prepare("UPDATE tasks SET task_title = ?, discription = ?, deadline = ? WHERE task_id = ?");
        $stmt->execute([$task_title, $task_description, $task_deadline, $task_id]);

        // Remove existing assignments
        $stmt = $connection->prepare("DELETE FROM task_assignment WHERE task_id = ?");
        $stmt->execute([$task_id]);

        // Add new assignments
        $stmt = $connection->prepare("SELECT student_id FROM users WHERE student_id = ?");
        $insertStmt = $connection->prepare("INSERT INTO task_assignment (task_id, student_id, status) VALUES (?, ?, 'Pending')");
        foreach ($assigned_students as $student_id) {
            $stmt->execute([$student_id]);
            if ($stmt->fetch()) {
                $insertStmt->execute([$task_id, $student_id]);
            }
        }
        echo json_encode(['res' => 'success', 'msg' => 'Task updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['res' => 'error', 'msg' => 'Failed to update task: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['res' => 'error', 'msg' => 'Invalid input']);
}
