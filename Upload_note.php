<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location='login.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "1974", "student_portal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    $file = $_FILES['note_file'];
    $filename = $file['name'];
    $filepath = 'uploads/' . basename($filename);
    $filetype = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

    $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

    if (in_array($filetype, $allowed)) {
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $stmt = $conn->prepare("INSERT INTO notes (user_id, subject, description, filename) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $subject, $description, $filename);
            $stmt->execute();
            echo "<script>alert('Note uploaded successfully!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Failed to upload file.');</script>";
        }
    } else {
        echo "<script>alert('Only PDF, DOC, and PPT files are allowed.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Notes - Student Notes Sharing Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f0f2f5;
    }
    .upload-container {
      max-width: 600px;
      margin: 70px auto;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      padding: 40px;
    }
    .form-control {
      border-radius: 8px;
      padding: 10px 14px;
    }
    .btn i {
      margin-right: 5px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="upload-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="text-primary mb-0"><i class="bi bi-upload"></i> Upload New Notes</h3>
      <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left-circle"></i> Back
      </a>
    </div>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-book"></i> Subject</label>
        <input type="text" name="subject" class="form-control" placeholder="Enter subject name (e.g., Data Structures)" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-pencil-square"></i> Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Brief description about the notes" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-file-earmark-arrow-up"></i> Select File</label>
        <input type="file" name="note_file" class="form-control" required>
        <small class="text-muted">Accepted formats: PDF, DOC, DOCX, PPT, PPTX</small>
      </div>

      <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-cloud-arrow-up"></i> Upload Note
        </button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
