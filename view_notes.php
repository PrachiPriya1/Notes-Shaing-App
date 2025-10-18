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

// Fetch notes
$sql = "SELECT * FROM notes ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Notes - Student Notes Sharing Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f0f2f5;
    }
    .container {
      margin-top: 50px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    iframe {
      width: 100%;
      height: 250px;
      border-radius: 8px;
      border: 1px solid #ddd;
      margin-top: 10px;
    }
    .btn-sm i {
      margin-right: 5px;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary"><i class="bi bi-journal-text"></i> Available Notes</h3>
    <a href="dashboard.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
    </a>
  </div>

  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title text-capitalize">
                <i class="bi bi-book text-info"></i> <?php echo htmlspecialchars($row['subject']); ?>
              </h5>
              <p class="card-text text-muted"><?php echo htmlspecialchars($row['description']); ?></p>

              <?php
                $ext = strtolower(pathinfo($row['filename'], PATHINFO_EXTENSION));
                $filepath = "uploads/" . $row['filename'];

                if ($ext === 'pdf') {
                    echo "<iframe src='$filepath'></iframe>";
                } else {
                    echo "<p class='text-danger'><i class='bi bi-file-earmark'></i> Preview not available for .$ext files</p>";
                }
              ?>

              <div class="mt-3 d-flex justify-content-between">
                <a href="<?php echo $filepath; ?>" download class="btn btn-success btn-sm">
                  <i class="bi bi-download"></i> Download
                </a>
                <a href="<?php echo $filepath; ?>" target="_blank" class="btn btn-info btn-sm text-white">
                  <i class="bi bi-eye"></i> View
                </a>
                <?php if ($row['user_id'] == $user_id): ?>
                  <a href="delete_note.php?id=<?php echo $row['id']; ?>" 
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Are you sure you want to delete this note?');">
                    <i class="bi bi-trash"></i> Delete
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="text-center text-muted">
        <i class="bi bi-exclamation-circle"></i> No notes available yet.
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
