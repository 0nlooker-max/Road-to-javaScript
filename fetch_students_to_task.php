<?php
require 'connection.php';

try {
    // Fetch verified students with role = 'student'
    $stmt = $connection->prepare("SELECT student_id, first_name, last_name FROM users WHERE is_verified = 1 AND role = 'student'");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($students);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch students: ' . $e->getMessage()]);
}
?>