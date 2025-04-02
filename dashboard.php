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
    $stmt = $connection->prepare("SELECT first_name, last_name, profile_image FROM users WHERE student_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $profile_image = $user['profile_image'] ? $user['profile_image'] : 'https://via.placeholder.com/80'; // Default image if none is set
    } else {
        $first_name = "Unknown";
        $last_name = "User";
        $profile_image = 'https://via.placeholder.com/80'; // Default image
    }
} catch (Exception $e) {
    $first_name = "Error";
    $last_name = "User";
    $profile_image = 'https://via.placeholder.com/80'; // Default image
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
        <div>
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
            <p><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></p>
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
                        <p class="card-text">342</p>
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
                            <th scope="col">Action</th>
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
                                <td><button type="button" class="btn btn-danger delete-btn" data-id="">Delete</button></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
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