<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Not logged in');
}

$student_id = $_SESSION['user_id'];
$task_id = $_POST['task_id'] ?? null;
$attach_link = $_POST['attach_link'] ?? null;
$attach_file = null;

// Handle file upload
if (!empty($_FILES['attach_file']['name'])) {
    $target_dir = "uploads/";
    $filename = uniqid() . "_" . basename($_FILES["attach_file"]["name"]);
    $target_file = $target_dir . $filename;
    if (move_uploaded_file($_FILES["attach_file"]["tmp_name"], $target_file)) {
        $attach_file = $target_file;
    }
}

// Update task_assignment
$stmt = $connection->prepare("UPDATE task_assignment SET status='Submitted', attach_link=?, attach_file=? WHERE task_id=? AND student_id=?");
$stmt->execute([$attach_link, $attach_file, $task_id, $student_id]);

echo "success";