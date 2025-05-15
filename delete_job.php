<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobId = $_POST['job_id'] ?? null;

    if (!empty($jobId)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM posted_jobs WHERE job_id = ?");
            $stmt->execute([$jobId]);

            if ($stmt->rowCount() > 0) {
                echo "<p class='alert alert-success'>Job deleted successfully!</p>";
            } else {
                echo "<p class='alert alert-warning'>No job found with that ID.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='alert alert-danger'>Error deleting job: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='alert alert-warning'>Please enter a valid job ID.</p>";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Delete Job Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h2 class="mb-4">🗑️ Delete Job Listing</h2>
    <form method="post">
        <div class="mb-3">
            <label for="job_id" class="form-label">Job ID</label>
            <input type="number" id="job_id" name="job_id" class="form-control" min="1" required>
        </div>
        <button type="submit" class="btn btn-danger">Delete Job</button>
    </form>
</div>
</body>
</html>
