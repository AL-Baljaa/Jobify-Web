<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['company_name'] ?? '';
    $location = $_POST['location'] ?? '';
    $industry = $_POST['industry'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($name) && !empty($location)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO employers (company_name, location, industry, description)
                                   VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $location, $industry, $description]);
            echo "<p class='alert alert-success'>Employer added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p class='alert alert-danger'>Error adding employer: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='alert alert-warning'>Please fill in all required fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Add New Employer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h2 class="mb-4">➕ Add New Employer</h2>
    <form method="post">
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" id="company_name" name="company_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" id="location" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="industry" class="form-label">Industry</label>
            <input type="text" id="industry" name="industry" class="form-control">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add Employer</button>
    </form>
</div>
</body>
</html>