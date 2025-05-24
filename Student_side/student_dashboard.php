<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php?message=not_logged_in");
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

// Fetch task counts
try {
    // Pending
    $stmt = $connection->prepare("SELECT COUNT(*) FROM task_assignment WHERE student_id = :student_id AND status = 'Pending'");
    $stmt->bindParam(':student_id', $user_id);
    $stmt->execute();
    $pending_tasks = $stmt->fetchColumn();

    // Completed
    $stmt = $connection->prepare("SELECT COUNT(*) FROM task_assignment WHERE student_id = :student_id AND status = 'Completed'");
    $stmt->bindParam(':student_id', $user_id);
    $stmt->execute();
    $completed_tasks = $stmt->fetchColumn();

    // Submitted
    $stmt = $connection->prepare("SELECT COUNT(*) FROM task_assignment WHERE student_id = :student_id AND status = 'Submitted'");
    $stmt->bindParam(':student_id', $user_id);
    $stmt->execute();
    $submitted_tasks = $stmt->fetchColumn();
} catch (Exception $e) {
    $pending_tasks = $completed_tasks = $submitted_tasks = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="dash.css">
</head>

<body>
    <div class="sidebar">
        <div>
            <h4>Dashboard Menu</h4>
            <a href="student_dashboard.php" class="bi bi-house"> Home</a>
            <a href="#" class="bi bi-people"> Users</a>
            <a href="Task.php" class="bi bi-people"> Task</a>
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
        <div class="dashboard-header">
            <h3>Dashboard</h3>
            <p id="dateTime"></p>
        </div>
        <div class="row">
            <div class="col-md-4 equal-height">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title text-warning"><i class="bi bi-hourglass-split"></i> Pending Tasks</h5>
                        <p class="card-text fs-2"><?php echo $pending_tasks; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 equal-height">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title text-info"><i class="bi bi-upload"></i> Submitted Tasks</h5>
                        <p class="card-text fs-2"><?php echo $submitted_tasks; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 equal-height">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title text-success"><i class="bi bi-check-circle"></i> Completed Tasks</h5>
                        <p class="card-text fs-2"><?php echo $completed_tasks; ?></p>
                    </div>
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
        <script src="dashboard.js"></script>
        <script src="login.js"></script>
</body>
</html>