<?php
// filepath: c:\xampp\htdocs\JavaScript\Road-to-javaScript\dashboard.php

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html?message=not_logged_in");
    exit();
}

// Get the user's email from the session
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : "User";

// Include database connection
require 'connection.php';

// Query to count the total number of users
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
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            height: 100%;
        }
        .logout-btn {
            margin-top: auto;
        }
        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
        }
        .dashboard-header {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .equal-height {
            display: flex;
            align-items: stretch;
        }
    </style>
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
            <img src="https://via.placeholder.com/80" alt="Profile Picture">
            <p>John Doe</p>
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
                                <td class="course">course</td>
                                <td class="address">address</td>
                                <td><button type="button" class="btn btn-danger" id="delete">Delete</button></td>     
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
