<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: index.php");
    exit;
}

require_once "config.php";

// Fetch distinct courses and user data grouped by course
$courses = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT course FROM users");
    $courses = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $users_by_course = [];
    foreach ($courses as $course) {
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE course = :course");
        $stmt->bindParam(":course", $course, PDO::PARAM_STR);
        $stmt->execute();
        $users_by_course[$course] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="navigation.css">

    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Online Examination System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleSection('dashboard')">Dashboard</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageSystemDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Manage System
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="manageSystemDropdown">
                    <a class="dropdown-item" href="addexam.php">Create Exam</a>
                    <a class="dropdown-item" href="feedback.php">Feedback</a>
                    <a class="dropdown-item" href="pageexam.php">Exam Created</a>
                    <a class="dropdown-item" href="register_admin.php">Register an Admin</a>
                    <a class="dropdown-item" href="user_rank.php">User Rank</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>


    <div class="container mt-4">
        <!-- Dashboard Section -->
        <div id="dashboard" class="section">
            <h2 class="text-center mb-4">Admin Dashboard</h2>
            <div class="row">
                <?php foreach ($users_by_course as $course => $users): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header text-center bg-primary text-white">
                                <?php echo htmlspecialchars($course); ?> Students
                            </div>
                            <div class="card-body">
                                <input type="text" class="form-control mb-3 search-bar" placeholder="Search <?php echo htmlspecialchars($course); ?>..." onkeyup="filterTable(this)">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped user-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($users) > 0): ?>
                                                <?php foreach ($users as $user): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">No users found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        

    <script>
        // Toggle visibility of sections
        function toggleSection(sectionId) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => {
                section.style.display = section.id === sectionId ? 'block' : 'none';
            });
        }

        // Function to filter the table based on search input
        function filterTable(input) {
            const filter = input.value.toLowerCase();
            const table = input.nextElementSibling.querySelector(".user-table tbody");
            const rows = table.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td");
                let showRow = false;

                // Check if any cell contains the filter text
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        showRow = true;
                        break;
                    }
                }

                rows[i].style.display = showRow ? "" : "none";
            }
        }
    </script>
</body>
</html>
