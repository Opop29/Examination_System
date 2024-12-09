<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: index.php");
    exit;
}

require_once "config.php";

// Fetch exam results grouped by exam
$exam_results = [];
try {
    $stmt = $pdo->prepare("
        SELECT 
            
            e.title AS exam_title, 
            u.username, 
            r.total_correct, 
            r.total_points, 
            r.total_questions, 
            r.created_at
        FROM 
            exam_results r
        JOIN 
            users u ON r.user_id = u.id
        JOIN 
            exams e ON r.exam_id = e.id
        ORDER BY 
            e.id, r.total_correct DESC
    ");
    $stmt->execute();
    $exam_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching exam results: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Rankings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .grid-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .grid-item h4 {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            margin: 0;
            border-radius: 5px 5px 0 0;
        }
        table {
            margin: 15px;
            font-size: 14px;
        }
        table thead {
            background-color: #f8f9fa;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="manageSystemDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage System</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="manageSystemDropdown">
                        <a class="dropdown-item" href="addexam.php">Create Exam</a>
                        <a class="dropdown-item" href="feedback.php">Feedback</a>
                        <a class="dropdown-item" href="pageexam.php">Exam Created</a>
                        <a class="dropdown-item" href="register_admin.php">Register an Admin</a>
                        <a class="dropdown-item" href="user_rank.php">User Rank</a>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <h2 class="text-center mt-4">User Rankings by Exam</h2>
        <div class="grid-container">
            <?php 
            $grouped_results = [];
            foreach ($exam_results as $result) {
                $grouped_results[$result['exam_title']][] = $result;
            }

            foreach ($grouped_results as $exam_title => $results): ?>
                <div class="grid-item">
                    <h4><?php echo htmlspecialchars($exam_title); ?></h4>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Score</th>
                                <th>Total Questions</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($result['username']); ?></td>
                                    <td><?php echo htmlspecialchars($result['total_correct']) . "/" . htmlspecialchars($result['total_points']); ?></td>
                                    <td><?php echo htmlspecialchars($result['total_questions']); ?></td>
                                    <td><?php echo htmlspecialchars($result['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
