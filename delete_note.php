<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location='login.php';</script>";
    exit();
}

$conn = new mysqli("localhost", "root", "1974", "student_portal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$note_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get filename
$stmt = $conn->prepare("SELECT filename FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $note_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $filepath = "uploads/" . $row['filename'];

    // Delete from database
    $conn->query("DELETE FROM notes WHERE id = $note_id AND user_id = $user_id");

    // Delete file from server
    if (file_exists($filepath)) {
        unlink($filepath);
    }

    echo "<script>alert('Note deleted successfully.'); window.location='view_notes.php';</script>";
} else {
    echo "<script>alert('Unauthorized access or note not found.'); window.location='view_notes.php';</script>";
}
?>
