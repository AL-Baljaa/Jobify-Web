<?php
require 'database.php';
$results = [];
$searchQuery = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchQuery = htmlspecialchars($_POST['search_query']);
    if (!empty($searchQuery)) {
        try {
            $stmt = $pdo->prepare("SELECT j.title, e.company_name AS company, j.location, j.salary 
                                   FROM posted_jobs j
                                   JOIN employers e ON j.employer_id = e.employer_id
                                   WHERE j.title LIKE ? OR j.location LIKE ?");
            $likeTerm = "%$searchQuery%";
            $stmt->execute([$likeTerm, $likeTerm]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p class='text-danger'>Database error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Job Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h2 class="mb-4">🔍 Search Job Listings</h2>
    <form method="post" class="mb-4">
        <div class="input-group mb-3">
            <input type="text" name="search_query" class="form-control" placeholder="Enter job title or location" value="<?= $searchQuery ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (!empty($results)): ?>
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['company'] ?></td>
                        <td><?= $row['location'] ?></td>
                        <td><?= $row['salary'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-info">No jobs found for your search.</div>
    <?php endif; ?>
</div>
</body>
</html>