<?php
require 'vendor/autoload.php'; // Load PHPMailer
require 'connection.php'; // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$phone_number = $_POST['phone_number'];
$course = $_POST['course'];
$user_address = $_POST['user_address'];
$birthdate = $_POST['birthdate'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$verification_code = bin2hex(random_bytes(16)); // Generate a random verification code

// Insert user into the database
$stmt = $connection->prepare("INSERT INTO users (first_name,last_name, email, gender, phone_number, course, user_address,birthdate, user_password,
verification_code) VALUES (:first_name,:last_name, :email, :gender, :phone_number, :course, :user_address, :birthdate, :password,
:verification_code)");
$stmt->bindParam(':first_name', $first_name);
$stmt->bindParam(':last_name', $last_name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':phone_number', $phone_number);
$stmt->bindParam(':course', $course);
$stmt->bindParam(':user_address', $user_address);
$stmt->bindParam(':birthdate', $birthdate);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':verification_code', $verification_code);

if ($stmt->execute()) {
// Send verification email

$mail = new PHPMailer(true);
try {
//Server settings
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
$mail->SMTPAuth = true;
$mail->Username = 'rexpagatpat@gmail.com'; // SMTP username
$mail->Password = 'pinx dhrz xtwy attv'; // SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

//Recipients
$mail->setFrom('rexpagatpat@gmail.com', 'Mailer');
$mail->addAddress($email); // Add a recipient

// Content
$mail->isHTML(true);
$mail->Subject = 'Account Verification';
$mail->Body = 'Click the link to verify your account: <a
href="http://localhost/JavaScript/Road-to-javaScript/verify.php?code=' . $verification_code . '">Verify Account</a>';

$mail->send();
echo 'Registration successful! Please check your email to verify your account.';
} catch (Exception $e) {
echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
} else {
echo "Error: Could not register user.";
}

}
?>

<!-- filepath: c:\xampp\htdocs\JavaScript\Road-to-javaScript\signup.php
<form method="post" class="p-4 border rounded shadow-sm bg-light" style="max-width: 400px; margin: auto;">
    <div class="container">  
        <h2 class="text-center mb-4">Register</h2>
    
        <div class="mb-3">
            <label for="fname" class="form-label">First Name:</label>
            <input type="text" id="fname" name="fname" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="lname" class="form-label">Last Name:</label>
            <input type="text" id="lname" name="lname" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="birthdate" class="form-label">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </div>
</form> -->

