<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include database configuration file
require_once "config.php";

// Validate the exam ID
if (!isset($_GET['exam_id']) || empty($_GET['exam_id'])) {
    echo "Invalid exam ID.";
    exit;
}

$exam_id = $_GET['exam_id'];

// Fetch the user's answers to the specific exam
$user_id = $_SESSION['id']; // Assuming session contains the logged-in user's ID
$sql = "SELECT 
            q.id AS question_id, 
            q.question_text, 
            a.answer AS user_answer, 
            q.correct_answer 
        FROM 
            exam_questions q
        LEFT JOIN 
            exam_answers a 
        ON 
            q.id = a.question_id AND a.user_id = :user_id
        WHERE 
            q.exam_id = :exam_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
$stmt->execute();
$answered_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the exam details to get points per question and total questions
$sql = "SELECT points_per_question, num_questions FROM exam_meta WHERE id = :exam_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
$stmt->execute();
$exam_meta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam_meta) {
    echo "Invalid exam details.";
    exit;
}

// Ensure that the points_per_question exists
$points_per_question = $exam_meta['points_per_question'] ?? 0;
$total_questions = $exam_meta['num_questions'] ?? 0;

// Fetch the user's exam results
$sql = "SELECT total_correct, total_points FROM exam_results WHERE user_id = :user_id AND exam_id = :exam_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
$stmt->execute();
$exam_results = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam_results) {
    echo "No results found for this exam.";
    exit;
}

// Calculate score status (e.g., Pass/Fail based on a score threshold)
$passing_score = $total_questions * $points_per_question * 0.6; // Assuming 60% is passing
$is_passing = $exam_results['total_points'] >= $passing_score;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="exam_results.css">
    <style>/* exam_results.css */
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .badge {
            font-size: 90%;
            padding: 5px 10px;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-info {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="dashboard.php">Dashboard</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Exam Results for Exam ID: <?php echo htmlspecialchars($exam_id); ?></h2>
    
    <!-- Display score status based on passing score -->
    <div class="alert <?php echo $is_passing ? 'alert-success' : 'alert-danger'; ?> mb-4">
        <h4 class="alert-heading"><?php echo $is_passing ? 'Congratulations!' : 'Unfortunately, you did not pass.'; ?></h4>
        <p><strong>Your Score:</strong> <?php echo $exam_results['total_points']; ?> / <?php echo $total_questions * $points_per_question; ?></p>
        <p><strong>Correct Answers:</strong> <?php echo $exam_results['total_correct']; ?></p>
        <p><strong>Incorrect Answers:</strong> <?php echo $total_questions - $exam_results['total_correct']; ?></p>
    </div>

    <h3>Detailed Results:</h3>
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($answered_questions)): ?>
            <?php foreach ($answered_questions as $question): ?>
                <tr>
                    <td><?php echo htmlspecialchars($question['question_text']); ?></td>
                    <td>
                        <?php 
                        echo htmlspecialchars($question['user_answer'] ?? "No Answer"); 
                        if (empty($question['user_answer'])) echo '<span class="badge badge-warning ml-2">Unanswered</span>'; 
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($question['correct_answer'] ?? "No Correct Answer"); ?></td>
                    <td>
                        <?php if (!empty($question['user_answer']) && $question['user_answer'] === $question['correct_answer']): ?>
                            <span class="badge badge-success">Correct</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Incorrect</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center text-muted">No questions available for this exam.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-primary mt-4">Back to Dashboard</a>
</div>

</body>
</html>
