<?php
require 'connection.php';

if (isset($_GET['assignment_id'])) {
    $submission_id = $_GET['assignment_id'];
    echo $submission_id;
    try {
        $stmt = $connection->prepare("UPDATE task_assignment SET status = 'Completed' WHERE assignment_id = ?");
        $stmt->execute([$submission_id]);

        echo json_encode(['res' => 'success', 'msg' => 'Submission marked as completed']);
    } catch (Exception $e) {
        echo json_encode(['res' => 'error', 'msg' => 'Failed to mark submission as completed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['res' => 'error', 'msg' => 'Submission ID not provided']);
}
?>