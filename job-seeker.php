<?php
// Include database connection
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (empty($_POST['name']) || empty($_POST['title']) || empty($_POST['skills']) || empty($_POST['about'])) {
        die("<p style='color: red;'>All fields are required.</p>");
    }

    $name = htmlspecialchars(trim($_POST['name']));
    $title = htmlspecialchars(trim($_POST['title']));
    $skills = htmlspecialchars(trim($_POST['skills']));
    $about = htmlspecialchars(trim($_POST['about']));

    // Handle CV upload
    $cvPath = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($_FILES['cv']['type'], $allowedTypes)) {
            die("<p style='color: red;'>Only PDF and Word documents are allowed.</p>");
        }

        if ($_FILES['cv']['size'] > 5 * 1024 * 1024) {
            die("<p style='color: red;'>File size must not exceed 5MB.</p>");
        }

        // Create uploads directory if it doesn't exist
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $fileExt = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('cv_', true) . '.' . $fileExt;
        $filePath = $uploadDir . $fileName;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['cv']['tmp_name'], $filePath)) {
            die("<p style='color: red;'>Failed to upload CV file.</p>");
        }

        $cvPath = $filePath;
    } else {
        die("<p style='color: red;'>Please upload a CV file.</p>");
    }

    try {
        // Insert into job_seekers table
        $stmt = $pdo->prepare("INSERT INTO job_seekers (full_name, job_title, skills, about, cv_path)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $title, $skills, $about, $cvPath]);

        echo "<p style='color: green;'>Profile saved successfully!</p>";
    } catch (PDOException $e) {
        die("<p style='color: red;'>Error saving profile: " . $e->getMessage() . "</p>");
    }
}

// Retrieve all job seekers from database
try {
    $stmt = $pdo->query("SELECT full_name, job_title, skills, about, cv_path FROM job_seekers ORDER BY created_at DESC");
    $seekers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p style='color: red;'>Database error: " . $e->getMessage() . "</p>");
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <title>Job Seeker Profiles</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        h1 { color: #333; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Submitted Job Seeker Profiles</h1>

<!-- Profile Table -->
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Title</th>
            <th>Skills</th>
            <th>About</th>
            <th>CV</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($seekers)): ?>
            <?php foreach ($seekers as $seeker): ?>
                <tr>
                    <td><?= $seeker['full_name'] ?></td>
                    <td><?= $seeker['job_title'] ?></td>
                    <td><?= $seeker['skills'] ?></td>
                    <td><?= substr($seeker['about'], 0, 100) ?>...</td>
                    <td><a href="<?= $seeker['cv_path'] ?>" target="_blank">Download CV</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No job seekers have been registered yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>