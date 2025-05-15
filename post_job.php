<?php
require 'database.php';

class JobPosting {
    public $title;
    public $company;
    public $location;
    public $description;
    public $salary;

    public function __construct($title, $company, $location, $description, $salary) {
        $this->title = htmlspecialchars($title);
        $this->company = htmlspecialchars($company);
        $this->location = htmlspecialchars($location);
        $this->description = htmlspecialchars($description);
        $this->salary = htmlspecialchars($salary);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['job-title'] ?? '';
    $company = $_POST['company-name'] ?? '';
    $location = $_POST['job-location'] ?? '';
    $description = $_POST['job-description'] ?? '';
    $salary = $_POST['salary'] ?? '';

    if (!empty($title) && !empty($company)) {
        try {
            // Get employer_id based on company name
            $stmt = $pdo->prepare("SELECT employer_id FROM employers WHERE company_name = ?");
            $stmt->execute([$company]);
            $employer = $stmt->fetch();

            if ($employer) {
                $employer_id = $employer['employer_id'];

                // Insert into posted_jobs
                $stmt = $pdo->prepare("INSERT INTO posted_jobs (employer_id, title, salary, description, location)
                                       VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$employer_id, $title, $salary, $description, $location]);

                echo "<p class='alert alert-success'>Job posted successfully!</p>";
            } else {
                echo "<p class='alert alert-warning'>Employer not found. Please save the employer profile first.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='alert alert-danger'>Error posting job: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Job Postings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        h1 { color: #333; }
    </style>
</head>
<body>

<div class="container my-4">
    <h1 class="mb-4">Submitted Job Postings</h1>

    <?php
    function displayJobPostings($pdo): void {
        $stmt = $pdo->query("
            SELECT j.title, e.company_name AS company, j.location, j.description, j.salary 
            FROM posted_jobs j
            JOIN employers e ON j.employer_id = e.employer_id
            ORDER BY j.posted_at DESC
        ");
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<table class="table table-bordered table-striped">';
        echo '<thead><tr><th>Title</th><th>Company</th><th>Location</th><th>Description</th><th>Salary</th></tr></thead>';
        echo '<tbody>';

        foreach ($jobs as $job) {
            echo '<tr>';
            echo '<td>' . $job['title'] . '</td>';
            echo '<td>' . $job['company'] . '</td>';
            echo '<td>' . $job['location'] . '</td>';
            echo '<td>' . substr($job['description'], 0, 50) . '...</td>';
            echo '<td>' . $job['salary'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    if ($pdo->query("SELECT COUNT(*) FROM posted_jobs")->fetchColumn() > 0) {
        displayJobPostings($pdo);
    } else {
        echo '<p class="alert alert-info">No job postings have been submitted yet.</p>';
    }
    ?>
</div>

</body>
</html>