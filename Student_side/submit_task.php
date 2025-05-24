<?php
session_start();
require 'connection.php';
require_once '../audit_log.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$student_id = $_SESSION['user_id'];
$task_id = $_POST['task_id'] ?? null;
$attach_link = $_POST['attach_link'] ?? null;
$attach_file = null;

// Validate task_id
if (!$task_id) {
    log_audit($connection, $student_id, 'submit_assignment_failed', 'Task ID missing');
    echo json_encode(['status' => 'error', 'message' => 'Task ID is required']);
    exit();
}

// Handle file upload
if (!empty($_FILES['attach_file']['name'])) {
    $target_dir = dirname(__DIR__) . "/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $filename = uniqid() . "_" . basename($_FILES["attach_file"]["name"]);
    $target_file = $target_dir . $filename;

    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['pdf', 'doc', 'docx'];

    if (!in_array($fileType, $allowedTypes)) {
        log_audit($connection, $student_id, 'submit_assignment_failed', 'Invalid file type');
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only PDF, DOC, DOCX allowed.']);
        exit();
    }

    if (move_uploaded_file($_FILES["attach_file"]["tmp_name"], $target_file)) {
        // Save relative path for DB
        $attach_file = "/uploads/" . $filename; // Note the leading slash!
    } else {
        log_audit($connection, $student_id, 'submit_assignment_failed', 'File upload failed');
        echo json_encode(['status' => 'error', 'message' => 'File upload failed.']);
        exit();
    }
}

// Update task_assignment
try {
    $stmt = $connection->prepare("UPDATE task_assignment SET status='Submitted', attach_link=?, attach_file=? WHERE task_id=? AND student_id=?");
    $stmt->execute([$attach_link, $attach_file, $task_id, $student_id]);
    log_audit($connection, $student_id, 'submit_assignment', "Submitted assignment for task ID: $task_id");
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    log_audit($connection, $student_id, 'submit_assignment_failed', 'Database error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}