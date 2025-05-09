

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link logoutBtn">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5 ">
        <h1 >STUDENTS TABLE</h1>
        
        <button type="button" class="btn btn-primary" id="addBtn" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            Add Student
        </button>
        <div class="row">
            <table class="table table-light shadow p-3 mb-5 bg-white rounded">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">FirstName</th>
                        <th scope="col">LastName</th>
                        <th scope="col">Email</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Course</th>
                        <th scope="col">Address</th>
                        <th scope="col">Age</th>
                        <th scope="col">Profile</th>
                        <th scope="col">Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <template id="produtrowtemplate">
                        <tr>
                            <td class="tdId">id</td>
                            <td class="fname">Fname</td>
                            <td class="lname">Lname</td>
                            <td class="email">email</td>
                            <td class="gender">gender</td>
                            <td class="course">course</td>
                            <td class="address">address</td>
                            <td class="age">age</td>
                            <td><img class="profile-img" src="profiles/default.jpg" alt="Profile Picture" width="50" height="50"></td>
                            <td><button type="button" class="btn btn-danger" id="delete">Delete</button></td>
                            <td>
                                <button type="button" class="btn btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editStudentModal">Edit</button>
                            </td>
                            
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="col mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="course" class="form-label">Course</label>
                                <input type="text" class="form-control" id="course" name="course" required>
                            </div>
                            <div class="col mb-3">
                                <label for="userAddress" class="form-label">User Address</label>
                                <input type="text" class="form-control" id="userAddress" name="userAddress" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                        </div>
                        <div class="mb-3">
                            <label for="profileImage" class="form-label">Profile</label>
                            <input type="file" class="form-control" id="profileImage" name="profileImage">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm">
                    <!-- Hidden input for student ID -->
                    <input type="hidden" id="editStudentId" name="studentId">

                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="firstName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="lastName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGender" class="form-label">Gender</label>
                        <select class="form-control" id="editGender" name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editCourse" class="form-label">Course</label>
                        <input type="text" class="form-control" id="editCourse" name="course" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserAddress" class="form-label">User Address</label>
                        <input type="text" class="form-control" id="editUserAddress" name="userAddress" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBirthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="editBirthdate" name="birthdate" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProfile" class="form-label">Profile Picture</label>
                        <img id="currentProfileImg" src="" alt="Current Profile Picture" width="50" height="50" name="currentProfileImg">
                        <input type="file" class="form-control" id="editProfile" name="editProfile">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
    <script src="login.js"></script>
</body>
</html>