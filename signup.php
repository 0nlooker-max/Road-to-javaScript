<?php
require 'vendor/autoload.php'; // Load PHPMailer
require 'connection.php'; // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$name = $_POST['name'];
$address = $_POST['address'];
$birthdate = $_POST['birthdate'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$verification_code = bin2hex(random_bytes(16)); // Generate a random verification code

// Insert user into the database
$stmt = $conn->prepare("INSERT INTO users (name, address, birthdate, email, password,
verification_code) VALUES (:name, :address, :birthdate, :email, :password, :verification_code)");
$stmt->bindParam(':name', $name);
$stmt->bindParam(':address', $address);
$stmt->bindParam(':birthdate', $birthdate);
$stmt->bindParam(':email', $email);
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
$mail->setFrom('cosepcarljoshua@gmail.com', 'Mailer');
$mail->addAddress($email); // Add a recipient

// Content
$mail->isHTML(true);
$mail->Subject = 'Account Verification';
$mail->Body = 'Click the link to verify your account: <a
href="http://localhost/prelim_crud/verify.php?code=' . $verification_code . '">Verify Account</a>';

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

<form method="post">
Name: <input type="text" name="name" required><br>
Address: <input type="text" name="address" required><br>
Birthdate: <input type="date" name="birthdate" required><br>
Email: <input type="email" name="email" required><br>
Password: <input type="password" name="password" required><br>
<button type="submit">Register</button>
</form>