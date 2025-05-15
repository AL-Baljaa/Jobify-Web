<?php
require 'database.php';

class EmployerProfile {
    public $companyName;
    public $location;
    public $industry;
    public $description;
    public $logoPath;

    public function __construct($name, $location, $industry, $description, $logoPath = '') {
        $this->companyName = htmlspecialchars($name);
        $this->location = htmlspecialchars($location);
        $this->industry = htmlspecialchars($industry);
        $this->description = htmlspecialchars($description);
        $this->logoPath = $logoPath;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['company-name'] ?? '';
    $location = $_POST['location'] ?? '';
    $industry = $_POST['industry'] ?? '';
    $description = $_POST['company-description'] ?? '';

    if (!empty($name) && !empty($location)) {
        $logoPath = '';

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['logo']['tmp_name']);
            if (in_array($fileType, $allowedTypes)) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $fileName = uniqid('logo_', true) . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $logoPath = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
                    $logoPath = '';
                }
            }
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO employers (company_name, location, industry, description, logo_path)
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $location, $industry, $description, $logoPath]);
            echo "<p class='alert alert-success'>Employer profile saved successfully!</p>";
        } catch (PDOException $e) {
            echo "<p class='alert alert-danger'>Error saving employer profile: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Employer Profiles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; }
        h1 { color: #333; }
        table img.logo { max-height: 60px; vertical-align: middle; }
    </style>
</head>
<body>

<div class="container my-4">
    <h1 class="mb-4">Submitted Employer Profiles</h1>

    <?php
    function displayProfiles($pdo): void {
        $stmt = $pdo->query("SELECT company_name, location, industry, description, logo_path FROM employers ORDER BY created_at DESC");
        $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<table class="table table-bordered table-striped">';
        echo '<thead><tr><th>Company</th><th>Location</th><th>Industry</th><th>Description</th><th>Logo</th></tr></thead>';
        echo '<tbody>';

        foreach ($profiles as $profile) {
            echo '<tr>';
            echo '<td>' . $profile['company_name'] . '</td>';
            echo '<td>' . $profile['location'] . '</td>';
            echo '<td>' . $profile['industry'] . '</td>';
            echo '<td>' . substr($profile['description'], 0, 50) . '...</td>';
            echo '<td>';
            echo $profile['logo_path'] ? '<img src="' . $profile['logo_path'] . '" alt="Logo" class="logo" />' : 'No Logo';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    if ($pdo->query("SELECT COUNT(*) FROM employers")->fetchColumn() > 0) {
        displayProfiles($pdo);
    } else {
        echo '<p class="alert alert-info">No employer profiles have been submitted yet.</p>';
    }
    ?>
</div>

</body>
</html>