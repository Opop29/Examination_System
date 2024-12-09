<?php
session_start();

// Check if the user is logged in; if not, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php"; // Include your database connection

// Fetch user data from the database
$user_id = $_SESSION["id"];
$sql = "SELECT id, username, email, course, year_level FROM users WHERE id = :id";
if ($stmt = $pdo->prepare($sql)) {
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $user = $stmt->fetch();
    }
}
// Fetch user data and normalize course and year level
$course = strtolower(trim($user['course'])); // Normalize case and trim spaces
$year_level = strtolower(trim($user['year_level'])); // Normalize case and trim spaces

// Modify the SQL query to check for both user-specific and shared exams (all level and all course)
$exam_sql = "SELECT * FROM exam_meta WHERE 
             (LOWER(TRIM(course)) = :course OR LOWER(TRIM(course)) = 'all course') 
             AND (LOWER(TRIM(year_level)) = :year_level OR LOWER(TRIM(year_level)) = 'all level')";

$exam_stmt = $pdo->prepare($exam_sql);
$exam_stmt->bindParam(":course", $course, PDO::PARAM_STR);
$exam_stmt->bindParam(":year_level", $year_level, PDO::PARAM_STR);
$exam_stmt->execute();
$exams = $exam_stmt->fetchAll();

// Fetch completed exams for the user
$completed_exams_sql = "
    SELECT es.exam_id, es.completed_at, em.exam_title
    FROM exam_submissions es
    LEFT JOIN exam_meta em ON es.exam_id = em.id
    WHERE es.user_id = :user_id
";
$completed_exams_stmt = $pdo->prepare($completed_exams_sql);
$completed_exams_stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$completed_exams_stmt->execute();
$completed_exams = $completed_exams_stmt->fetchAll();

// Handle "Take Exam" button
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["take_exam"])) {
    $exam_id = $_POST["exam_id"];

    // Check if the exam is already completed
    $check_sql = "SELECT * FROM exam_submissions WHERE user_id = :user_id AND exam_id = :exam_id";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $check_stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
    $check_stmt->execute();

    if ($check_stmt->rowCount() === 0) {
        // Mark the exam as completed
        $insert_sql = "INSERT INTO exam_submissions (user_id, exam_id, completed_at) VALUES (:user_id, :exam_id, NOW())";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);

        if ($insert_stmt->execute()) {
            header("Location: exam_details.php?exam_id=" . $exam_id);
            exit;
        } else {
            echo "Error: Could not mark the exam as completed.";
        }
    } else {
        echo "You have already completed this exam.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .bg-blue {
            background-color: #007bff !important;
        }
        .navbar-nav .nav-link:hover {
            color: #f0f0f0 !important;
            background-color: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease-in-out;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-blue shadow-sm">
    <a class="navbar-brand text-white font-weight-bold" href="dashboard.php">Dashboard</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link text-white font-weight-bold" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-5">
    <div class="dashboard-grid">
        <!-- User Information -->
        <div class="card">
            <div class="card-header bg-primary text-white">User Information</div>
            <div class="card-body">
                <p>Username: <?php echo htmlspecialchars($user["username"]); ?></p>
                <p>Email: <?php echo htmlspecialchars($user["email"]); ?></p>
                <p>Course: <?php echo htmlspecialchars($user["course"]); ?></p>
                <p>Year Level: <?php echo htmlspecialchars($user["year_level"]); ?></p>
            </div>
        </div>
        <!-- Relevant Exams -->
        <div class="card">
            <div class="card-header bg-warning text-white">Relevant Exams</div>
            <div class="card-body">
                <?php if (!empty($exams)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Questions</th>
                                <th>Points/Question</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exams as $exam): ?>
                                <?php
                                $completed = array_filter($completed_exams, function($completed) use ($exam) {
                                    return $completed['exam_id'] == $exam['id'];
                                });
                                if (empty($completed)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($exam['exam_title']); ?></td>
                                        <td><?php echo htmlspecialchars($exam['num_questions']); ?></td>
                                        <td><?php echo htmlspecialchars($exam['points_per_question']); ?></td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
                                                <button type="submit" name="take_exam" class="btn btn-primary">Take Exam</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No exams available for your course and year level.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Completed Exams -->
        <div class="card">
            <div class="card-header bg-success text-white">Completed Exams</div>
            <div class="card-body">
                <?php if (!empty($completed_exams)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Completed At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($completed_exams as $completed): ?>
    <tr>
        <td><?php echo htmlspecialchars($completed['exam_title']); ?></td>
        <td><?php echo htmlspecialchars($completed['completed_at']); ?></td>
        <td>
            <a href="exam_results.php?exam_id=<?php echo $completed['exam_id']; ?>" class="btn btn-success">View Result</a>
        </td>
    </tr>
<?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No completed exams yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<footer class="text-center py-3 bg-dark text-white">
    <p>&copy; 2024 Exam System. All rights reserved.</p>
</footer>
</body>
</html>
