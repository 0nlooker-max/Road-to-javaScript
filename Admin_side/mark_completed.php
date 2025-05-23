<?php
require 'connection.php';

if (isset($_GET['submission_id'])) {
    $submission_id = $_GET['submission_id'];

    try {
        $stmt = $connection->prepare("UPDATE task_assignment SET status = 'Completed' WHERE submission_id = ?");
        $stmt->execute([$submission_id]);

        echo json_encode(['res' => 'success', 'msg' => 'Submission marked as completed']);
    } catch (Exception $e) {
        echo json_encode(['res' => 'error', 'msg' => 'Failed to mark submission as completed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['res' => 'error', 'msg' => 'Submission ID not provided']);
}
?>