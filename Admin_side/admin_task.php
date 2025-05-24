<?php
// filepath: c:\xampp\htdocs\JavaScript\Road-to-javaScript\dashboard.php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.html?message=not_logged_in");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="dash.css">
</head>

<body>
    <div class="sidebar">
        <div>
            <h4>Dashboard Menu</h4>
            <a href="admin_dashboard.php" class="home bi bi-house"> Home</a>
            <a href="#" class="bi bi-people"> Users</a>
            <a href="admin_task.php" class="task bi bi-people"> Task</a>
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

        <div class="card shadow mb-4">
            <div class="card-header">Task Manager</div>
            <div class="card-body">
                <form id="taskForm">
                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" id="taskTitle" name="task_title" placeholder="Task Title" required>
                        </div>
                        <div class="col-6">
                            <textarea class="form-control" id="taskDescription" name="task_description" placeholder="Task Description" required></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <select class="form-control" id="assignedStudent" name="assigned_student[]" multiple>
                                <option value="" disabled>Select Students</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="datetime-local" class="form-control" id="deadlineInput" name="due_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <div class="container mt-5 ">
            <div class="row">
                <table class="table table-light shadow p-3 mb-5 bg-white rounded">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Deadline</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodytask">
                        <template id="produtrowtemplatetask">
                            <tr>
                                <td class="title">title</td>
                                <td class="disc">description</td>
                                <td class="deads">deadline</td>

                                <td><button type="button" class="btn btn-warning bi bi-eye me-2" id="view" data-task-id="1"></button>
                                    <button type="button" class="btn btn-primary bi bi-pencil-square me-2 edit-btn" data-bs-toggle="modal" data-bs-target="#editTaskModal" data-task-id="1"></button>
                                    
                                </td>

                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editTaskForm">
                            <div class="mb-3">
                                <label for="editTaskTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="editTaskTitle" name="task_title" required>
                            </div>
                            <div class="mb-3">
                                <label for="editTaskDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editTaskDescription" name="task_description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editTaskDeadline" class="form-label">Deadline</label>
                                <input type="datetime-local" class="form-control" id="editTaskDeadline" name="task_deadline" required>
                            </div>
                            <div class="mb-3">
                                <label for="editAssignedStudents" class="form-label">Assign Students</label>
                                <select class="form-control" id="editAssignedStudents" name="assigned_students[]" multiple>
                                    <!-- Options will be dynamically populated -->
                                </select>
                            </div>
                            <input type="hidden" id="editTaskId">

                            <div class="mb-3">
                                <label>Assigned Students:</label>
                                <div id="assignedStudentsList" class="d-flex flex-wrap">
                                    <!-- Assigned students will be dynamically populated -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveTaskChanges">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskDetailsModalLabel">Task Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Title:</strong> <span id="modalTaskTitle"></span></p>
                        <p><strong>Description:</strong> <span id="modalTaskDescription"></span></p>
                        <p><strong>Deadline:</strong> <span id="modalTaskDeadline"></span></p>

                        <h6 class="text-danger">Pending</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>File</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="pendingAssignments"></tbody>
                        </table>

                        <h6 class="text-success">Submitted</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="submittedAssignments"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        <!-- view completed student -->
        <div class="modal fade" id="completedAssignmentsModal" tabindex="-1" aria-labelledby="completedAssignmentsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="completedAssignmentsModalLabel">Completed Assignments</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Date Submitted</th>
                                    <th>Task Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="completedAssignmentsBody"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="task.js"></script>
        <script src="dashboard.js"></script>
        <script>
            loadStudents();

            document.addEventListener('DOMContentLoaded', function() {
                const editTaskModal = document.getElementById('editTaskModal');
                if (editTaskModal) {
                    editTaskModal.addEventListener('hidden.bs.modal', function() {
                        // Remove lingering backdrop and modal-open class
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                    });
                }
            });

            function renderTaskRow(task) {
                const template = document.getElementById('produtrowtemplatetask');
                const clone = template.content.cloneNode(true);
                clone.querySelector('.title').textContent = task.title;
                clone.querySelector('.disc').textContent = task.description;
                clone.querySelector('.deads').textContent = task.deadline;

                // Set data-task-id for all relevant buttons
                clone.querySelector('.view-btn').setAttribute('data-task-id', task.task_id);
                clone.querySelector('.edit-btn').setAttribute('data-task-id', task.task_id);
                clone.querySelector('.view-completed-btn').setAttribute('data-task-id', task.task_id);

                document.getElementById('tableBodytask').appendChild(clone);
            }
        </script>



</body>

</html>