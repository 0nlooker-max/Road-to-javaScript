<?php
// filepath: c:\xampp\htdocs\JavaScript\Road-to-javaScript\dashboard.php

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html?message=not_logged_in");
    exit();
}

// Get the user's email and ID from the session
$user_id = $_SESSION['user_id'];
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : "User";

// Include database connection
require 'connection.php';

// Fetch user details from the database
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
        $profile_image = $user['profile_image'] ? $user['profile_image'] : '\profiles\fighting meme.webp'; // Default image if none is set
    } else {
        $first_name = "Unknown";
        $last_name = "User";
        $course = "";
        $phone_number = "";
        $user_address = "";
        $profile_image = '\profiles\fighting meme.webp'; // Default image
    }
} catch (Exception $e) {
    $first_name = "Error";
    $last_name = "User";
    $course = "";
    $phone_number = "";
    $user_address = "";
    $profile_image = '\profiles\fighting meme.webp'; // Default image
}



try {
    $stmt = $connection->prepare("SELECT COUNT(*) AS total_verified_users FROM users WHERE is_verified = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_verified_users = $result['total_verified_users'];
} catch (Exception $e) {
    $total_verified_users = "Error"; // Handle errors gracefully
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="dash.css">
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
        <div class="dashboard-header">
            <h3>Assign Task</h3>
            <p id="dateTime"></p>
        </div>

        <form id="taskForm" class="shadow p-4 mb-5 bg-white rounded">
            <h4 class="mb-4">Jove pisuttt</h4>

            <div class="row">
                <div class="mb-3 col">
                    <input type="text" class="form-control" id="taskTitle" name="task_title" placeholder="Task Title" required>
                </div>
                <div class="mb-3 col">
                    <input type="text" class="form-control" id="taskDescription" name="task_description" placeholder="Task Description" required>
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col">
                    <select class="form-control" id="assignedStudent" name="assigned_student[]" multiple required>
                        <option value="" disabled>Select Students</option>
                        <?php
                        try { 
                            $stmt = $connection->prepare("SELECT student_id, first_name, last_name FROM prilimtable");
                            $stmt->execute();
                            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($students as $student) {
                                echo '<option value="' . htmlspecialchars($student['student_id']) . '">'
                                    . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) .
                                    '</option>';
                            }
                        } catch (Exception $e) {
                            echo '<option value="">Error fetching students</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3 col">
                    <input type="datetime-local" class="form-control" id="dueDate" name="due_date" required>
                </div>
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-primary" id="AddTToggleBtn">Add Task</button>
            </div>
        </form>

        <div class="container mt-5 ">
            <div class="row">
                <table class="table table-light shadow p-3 mb-5 bg-white rounded">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Deadline</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tableBodytask">
                        <template id="produtrowtemplatetask">
                            <tr>
                                <td class="title">title</td>
                                <td class="disc">description</td>
                                <td class="deads">deadline</td>
                                <td class="stat">Status</td>
                                <td><button type="button" class="btn btn-warning bi bi-eye me-2" id="view"></button>
                                    <button type="button" class="btn btn-primary bi bi-pencil-square me-2 edit-btn" data-bs-toggle="modal" data-bs-target="#editStudentModal"></button>
                                    <button type="button" class="btn btn-danger bi bi-trash" id="delete"></button>
                                </td>

                            </tr>
                        </template>
                    </tbody>
                </table>
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
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="task.js"></script>
        <script src="login.js"></script>

</body>

</html>