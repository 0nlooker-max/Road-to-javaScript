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
    $stmt = $connection->prepare("SELECT COUNT(*) AS total_users FROM users");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_users = $result['total_users'];
} catch (Exception $e) {
    $total_users = "Error"; // Handle errors gracefully
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
            <a href="#" class="bi bi-house"> Home</a>
            <a href="#" class="bi bi-people"> Users</a>
            <a href="#" class="bi bi-gear"> Settings</a>
        </div>
        <div class="row">
            <button type="button" class="btn btn-link p-0 profile-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="img-thumbnail profile-img col">
                <p class="profile-name col"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></p>
            </button>
            <button class="btn btn-danger w-100 logout-btn">Logout</button>
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
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo htmlspecialchars($total_users); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 equal-height">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title">Status</h5>
                        <button class="btn btn-success">Active</button>
                        <button class="btn btn-danger">Inactive</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 equal-height">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title">Status</h5>
                        <button class="btn btn-success">Active</button>
                        <button class="btn btn-danger">Inactive</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5 ">
            <div class="row">
                <table class="table table-light shadow p-3 mb-5 bg-white rounded">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">FirstName</th>
                            <th scope="col">LastName</th>
                            <th scope="col">Course</th>
                            <th scope="col">Address</th>
                            <th scope="col">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <template id="produtrowtemplate">
                            <tr>
                                <td class="fname">Fname</td>
                                <td class="lname">Lname</td>
                                <td class="course">Course</td>
                                <td class="address">Address</td>
                                <td class="is_verified">Status</td>
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
                                    <input type="text" class="form-control" id="editFirstName" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                                </div>
                                <div class="mb-3 col">
                                    <label for="editLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="editLastName" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col">
                                    <label for="editCourse" class="form-label">Course</label>
                                    <input type="text" class="form-control" id="editCourse" name="course" value="<?php echo htmlspecialchars($course); ?>" required>
                                </div>
                                <div class="mb-3 col">
                                    <label for="editPnum" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="editPnum" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col">
                                    <label for="editAddress" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="editAddress" name="user_address" value="<?php echo htmlspecialchars($user_address); ?>" required>
                                </div>
                                <div class="mb-3 col">
                                    <label for="editProfileImage" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control" id="editProfileImage" name="profile_image">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
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
        <script src="dashboard.js"></script>
</body>

</html>