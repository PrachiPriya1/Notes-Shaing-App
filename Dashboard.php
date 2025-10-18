<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location='login.html';</script>";
    exit();
}

// Prevent back navigation after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "1974", "student_portal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's name
$result = $conn->query("SELECT name FROM users WHERE id = $user_id");
$row = $result->fetch_assoc();
$username = $row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Student Notes Sharing Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f0f2f5;
    }
    .dashboard-container {
      max-width: 900px;
      margin: 60px auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-custom {
      border-radius: 8px;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="dashboard-container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-person-circle text-primary"></i> Welcome, <?php echo htmlspecialchars($username); ?></h3>
        <a href="logout.php" class="btn btn-danger btn-sm"
           onclick="return confirm('Are you sure you want to logout?');">
           <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
      <hr>

      <div class="text-center mt-4">
        <h4><i class="bi bi-journal-text text-success"></i> Student Notes Sharing Portal</h4>
        <p class="text-muted">Easily upload, view, and manage your notes.</p>
      </div>

      <div class="row mt-5 text-center">
        <div class="col-md-6 mb-3">
          <a href="upload_note.php" class="btn btn-primary w-100 btn-custom py-3">
            <i class="bi bi-cloud-upload"></i> Upload Notes
          </a>
        </div>
        <div class="col-md-6 mb-3">
          <a href="view_notes.php" class="btn btn-info w-100 btn-custom py-3 text-white">
            <i class="bi bi-eye"></i> View Notes
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
