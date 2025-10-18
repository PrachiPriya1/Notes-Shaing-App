<?php
// Start a session (optional but good if you want to auto-login later)
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "1974", "student_portal");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['Password'];

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Email already registered! Please use another email.'); window.location='register.html';</script>";
} else {
    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (name, email, Password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please log in.'); window.location='login.html';</script>";
    } else {
        echo "<script>alert('Error while registering.'); window.location='registration.html';</script>";
    }
}

// Close connection
$conn->close();
?>
