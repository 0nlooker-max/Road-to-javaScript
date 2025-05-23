<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?message=not_logged_in");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : "User";

require 'connection.php';

// Fetch user details
try {
    $stmt = $connection->prepare("SELECT first_name, last_name, course, phone_number, user_address, profile_image FROM users WHERE student_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $course = $user['course'];
        $phone_number = $user['phone_number'];
        $user_address = $user['user_address'];
        $profile_image = $user['profile_image'] ? $user['profile_image'] : '\profiles\fighting meme.webp';
    } else {
        $first_name = "Unknown";
        $last_name = "User";
        $course = "";
        $phone_number = "";
        $user_address = "";
        $profile_image = '\profiles\fighting meme.webp';
    }
} catch (Exception $e) {
    $first_name = "Error";
    $last_name = "User";
    $course = "";
    $phone_number = "";
    $user_address = "";
    $profile_image = '\profiles\fighting meme.webp';
}

// Fetch assigned (not completed) tasks
try {
    $stmt = $connection->prepare("
        SELECT t.task_title, t.discription AS description, t.deadline, ta.status
        FROM task_assignment ta
        JOIN tasks t ON ta.task_id = t.task_id
        WHERE ta.student_id = :student_id AND ta.status != 'Completed'
        ORDER BY t.deadline ASC
    ");
    $stmt->bindParam(':student_id', $user_id);
    $stmt->execute();
    $assigned_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $assigned_tasks = [];
}

// Fetch completed tasks
try {
    $stmt = $connection->prepare("
        SELECT t.task_title, t.discription AS description, t.deadline, ta.status
        FROM task_assignment ta
        JOIN tasks t ON ta.task_id = t.task_id
        WHERE ta.student_id = :student_id AND ta.status = 'Completed'
        ORDER BY t.deadline DESC
    ");
    $stmt->bindParam(':student_id', $user_id);
    $stmt->execute();
    $completed_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $completed_tasks = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="dash.css">
    <style>
        body {
            background: #28263a;
            color: #fff;
        }

        .table-dark th,
        .table-dark td {
            color: #fff;
        }

        .table-light th,
        .table-light td {
            color: #222;
        }

        .dashboard-header h3 {
            color: #6c63ff;
            font-weight: bold;
        }

        h4,
        h3 {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div>
            <h4>Dashboard Menu</h4>
            <a href="student_dashboard.php" class="home bi bi-house"> Home</a>
            <a href="#" class="bi bi-people"> Users</a>
            <a href="Task.php" class="task bi bi-people"> Task</a>
            <a href="#" class="bi bi-gear"> Settings</a>
        </div>
        <div class="row">
            <button type="button" class="btn btn-link p-0 profile-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="img-thumbnail profile-img col">
                <p class="profile-name col"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></p>
            </button>
            <button class="btn btn-danger w-100 logoutBtn">Logout</button>
        </div>
    </div>
    <div class="content">
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <h3>MY ASSIGNED TASK</h3>
            <p id="dateTime" class="mb-0"></p>
        </div>

        <!-- Assigned Tasks Table -->
        <table class="table table-dark table-striped align-middle mt-4">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($assigned_tasks)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No assigned tasks.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($assigned_tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['task_title']) ?></td>
                            <td><?= htmlspecialchars($task['description']) ?></td>
                            <td><?= htmlspecialchars($task['deadline']) ?></td>
                            <td><?= htmlspecialchars($task['status']) ?></td>
                            <td>
                                <?php if (strtolower($task['status']) === 'pending'): ?>
                                    <button
                                        class="btn btn-info btn-sm attach-file-btn"
                                        data-task-title="<?= htmlspecialchars($task['task_title']) ?>"
                                        data-task-id="<?= htmlspecialchars($task['task_id'] ?? '') ?>">Attach File</button>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Completed Tasks Table -->
        <h4 class="mt-5 mb-2" style="color:white; font-weight:bold;">Completed Tasks</h4>
        <table class="table table-light align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($completed_tasks)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No completed tasks.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($completed_tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['task_title']) ?></td>
                            <td><?= htmlspecialchars($task['description']) ?></td>
                            <td><?= htmlspecialchars($task['deadline']) ?></td>
                            <td><b><?= htmlspecialchars($task['status']) ?></b></td>
                            <td>Actions</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Submit Task Modal -->
    <div class="modal fade" id="submitTaskModal" tabindex="-1" aria-labelledby="submitTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background:#28263a;color:#fff;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="submitTaskModalLabel" style="color:#19e6ff;">Submit Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="submitTaskForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="modalTaskId" name="task_id">
                        <div class="mb-3">
                            <label class="form-label" style="color:#19e6ff;">Upload your file (PDF or Word)</label>
                            <input type="file" class="form-control" name="attach_file" accept=".pdf,.doc,.docx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color:#19e6ff;">Or paste a link</label>
                            <input type="url" class="form-control" name="attach_link" placeholder="https://example.com">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn w-100" style="background:#19e6ff;color:#222;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Profile Edit Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="row">
                            <div class="mb-3 col">
                                <label for="editFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" readonly required>
                            </div>
                            <div class="mb-3 col">
                                <label for="editLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" readonly required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col">
                                <label for="editCourse" class="form-label">Course</label>
                                <input type="text" class="form-control" id="editCourse" name="course" value="<?php echo htmlspecialchars($course); ?>" readonly required>
                            </div>
                            <div class="mb-3 col">
                                <label for="editPnum" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="editPnum" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" readonly required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col">
                                <label for="editAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="editAddress" name="user_address" value="<?php echo htmlspecialchars($user_address); ?>" readonly required>
                            </div>
                            <div class="mb-3 col">
                                <label for="editProfileImage" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="editProfileImage" name="profile_image" disabled>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="editToggleBtn">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateDateTime() {
            const now = new Date();
            document.getElementById("dateTime").innerText = now.toLocaleString();
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
    <script src="student_task.js"></script>
</body>

</html>