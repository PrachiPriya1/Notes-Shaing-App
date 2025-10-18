<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "1974", "student_portal");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Fetch user based on email
$stmt = $conn->prepare("SELECT id, name, Password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['Password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        echo "<script>alert('Login successful!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Incorrect password.'); window.location='login.html';</script>";
    }
} else {
    echo "<script>alert('No user found with this email.'); window.location='login.html';</script>";
}

$conn->close();
?>
