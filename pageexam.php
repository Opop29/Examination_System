<?php
// Include database connection
require_once 'config.php';  // Include your database connection settings

// Handle delete exam request
if (isset($_GET['delete_exam_id'])) {
    $exam_id = $_GET['delete_exam_id'];

    try {
        // Delete the exam from the database
        $delete_sql = "DELETE FROM exam_meta WHERE id = :exam_id";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
        $delete_stmt->execute();

        // Redirect after deletion
        header("Location: pageexam.php");
        exit;

    } catch (PDOException $e) {
        $error_msg = "Failed to delete exam: " . $e->getMessage();
    }
}

// Fetch all questionnaires from the exam_meta table
$sql = "SELECT * FROM exam_meta";  // Fetch data from exam_meta table
$stmt = $pdo->prepare($sql);
$stmt->execute();
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle View Exam Request
if (isset($_GET['view_exam_id'])) {
    $exam_id = $_GET['view_exam_id'];

    // Fetch exam details from the exam_meta table
    $view_sql = "SELECT * FROM exam_meta WHERE id = :exam_id";
    $view_stmt = $pdo->prepare($view_sql);
    $view_stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
    $view_stmt->execute();
    $exam_details = $view_stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch questions from the exam_questions table
    $questions_sql = "SELECT * FROM exam_questions WHERE exam_id = :exam_id";
    $questions_stmt = $pdo->prepare($questions_sql);
    $questions_stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
    $questions_stmt->execute();
    $questions = $questions_stmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch exam results (rank) for the specific exam with course and year level info
$rank_sql = "SELECT er.user_id, er.total_correct, er.total_points, er.total_questions, er.created_at, u.course, u.year_level 
             FROM exam_results er
             JOIN users u ON er.user_id = u.id
             WHERE er.exam_id = :exam_id
             ORDER BY er.total_points DESC, er.total_correct DESC";
$rank_stmt = $pdo->prepare($rank_sql);
$rank_stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
$rank_stmt->execute();
$rank_results = $rank_stmt->fetchAll(PDO::FETCH_ASSOC);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="pageexam.css"> <!-- Include any additional styling -->
    <link rel="stylesheet" href="navigation.css">
    <style>/* Style for the User Rankings table */
.rank-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.rank-table thead {
    background-color: #4CAF50;
    color: white;
}

.rank-table th, .rank-table td {
    padding: 12px;
    text-align: left;
    font-size: 16px;
}

.rank-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.rank-table tr:hover {
    background-color: #f1f1f1;
    transition: background-color 0.3s ease;
}

.rank-table th {
    font-weight: bold;
    text-transform: uppercase;
}

.rank-table td {
    font-size: 15px;
    color: #333;
}

/* Add a subtle gradient effect on the header */
.rank-table thead {
    background: linear-gradient(90deg, #4CAF50, #45a049);
}

/* Highlight the rank column with a gradient background */
.rank-table td:first-child, .rank-table th:first-child {
    background: linear-gradient(90deg, #ff8c00, #ff7f00);
    color: white;
    font-weight: bold;
}
html {
    scroll-behavior: smooth;
}

/* Add a border around the table */
.rank-table {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.rank-table td, .rank-table th {
    border: 1px solid #ddd;
}

/* For small screens, make the table scrollable */
@media screen and (max-width: 768px) {
    .rank-table {
        display: block;
        overflow-x: auto;
    }

    .rank-table th, .rank-table td {
        white-space: nowrap;
    }
}
#backToTopBtn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: none; /* Hidden by default */
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

#backToTopBtn:hover {
    background-color: #0056b3;
}/* Style for the exam list (2x2 grid layout) */
.exam-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* Create 2 columns */
    gap: 20px; /* Space between the items */
    margin-top: 20px;
}

/* Style for each exam item */
.exam-item {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.exam-item:hover {
    transform: scale(1.05); /* Slight zoom effect on hover */
}

/* Adjustments for small screens (Responsive) */
@media screen and (max-width: 768px) {
    .exam-list {
        grid-template-columns: repeat(2, 1fr); /* Keep 2 columns on medium screens */
    }
}

@media screen and (max-width: 480px) {
    .exam-list {
        grid-template-columns: 1fr; /* Change to 1 column on small screens */
    }
}


</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="admin_dashboard.php">Online Examination System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
            </li>
            <!-- Manage System Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageSystemDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Manage System
                </a>
                <div class="dropdown-menu" aria-labelledby="manageSystemDropdown">
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

<!-- Main Content -->
<div class="container">
    <!-- Left Container (Exams Created) -->
    <div class="left-container">
        <h1>Existing Questionnaires</h1>

        <!-- Show Error Message if Deleting Fails -->
        <?php if (isset($error_msg)): ?>
            <div class="error"><?= $error_msg; ?></div>
        <?php endif; ?>

        <?php if (empty($exams)): ?>
            <p>No exams found.</p>
        <?php else: ?>
            <div class="exam-list">
                <?php foreach ($exams as $exam): ?>
                    <div class="exam-item">
                        <h2><?= htmlspecialchars($exam['exam_title']); ?></h2>
                        <p><strong>Course:</strong> <?= htmlspecialchars($exam['course']); ?></p>
                        <p><strong>Year Level:</strong> <?= htmlspecialchars($exam['year_level']); ?></p>
                        <p><strong>Questions:</strong> <?= htmlspecialchars($exam['num_questions']); ?> questions</p>
                        <p><strong>Points per Question:</strong> <?= htmlspecialchars($exam['points_per_question']); ?> points</p>
                        <div class="exam-actions">
                          <!-- Modify the View Button link -->
<a href="pageexam.php?view_exam_id=<?= $exam['id']; ?>#rank-table" class="view-btn">View</a> 

                            
                            <!-- Modify Button -->
                            <a href="pageexam.php?modify_exam_id=<?= $exam['id']; ?>" class="modify-btn">close</a>
                            
                            <!-- Delete Button -->
                            <a href="pageexam.php?delete_exam_id=<?= $exam['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this exam?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Container (View Exam Details) -->
    <div class="right-container">
        <?php if (isset($exam_details)): ?>
            <div class="view-exam-container">
                <h2>Exam Details</h2>
                <table class="exam-details-table">
                    <tr>
                        <th>Exam Title</th>
                        <td><?= htmlspecialchars($exam_details['exam_title']); ?></td>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <td><?= htmlspecialchars($exam_details['course']); ?></td>
                    </tr>
                    <tr>
                        <th>Year Level</th>
                        <td><?= htmlspecialchars($exam_details['year_level']); ?></td>
                    </tr>
                    <tr>
                        <th>Number of Questions</th>
                        <td><?= htmlspecialchars($exam_details['num_questions']); ?></td>
                    </tr>
                    <tr>
                        <th>Points per Question</th>
                        <td><?= htmlspecialchars($exam_details['points_per_question']); ?></td>
                    </tr>
                </table>

                <h3>Questions and Answers:</h3>
                <table class="exam-questions-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Correct Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $index => $question): ?>
                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($question['question_text']); ?></td>
                                <td>
                                    <?php if ($question['question_type'] == 'mcq'): ?>
                                        <strong>Multiple Choice:</strong>
                                        <?php
                                        $options = json_decode($question['options']);  // Decode options if stored as JSON
                                        if (is_array($options)): ?>
                                            <ul>
                                                <?php foreach ($options as $option): ?>
                                                    <li><?= htmlspecialchars($option); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    <?php elseif ($question['question_type'] == 'tf'): ?>
                                        True / False
                                    <?php elseif ($question['question_type'] == 'fill'): ?>
                                        Fill in the blank
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($question['correct_answer']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>User Rankings for this Exam:</h3>
<table class="rank-table" id="rank-table"> <!-- Added an ID to the table -->
    <thead>
        <tr>
            <th>Rank</th>
            <th>User ID</th>
            <th>Course</th> <!-- Added Course Column -->
            <th>Year Level</th> <!-- Added Year Level Column -->
            <th>Correct Answers</th>
            <th>Total Points</th>
            <th>Time Taken</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 1;
        foreach ($rank_results as $result): ?>
            <tr>
                <td><?= $rank++; ?></td>
                <td><?= htmlspecialchars($result['user_id']); ?></td>
                <td><?= htmlspecialchars($result['course']); ?></td> <!-- Display Course -->
                <td><?= htmlspecialchars($result['year_level']); ?></td> <!-- Display Year Level -->
                <td><?= htmlspecialchars($result['total_correct']); ?></td>
                <td><?= htmlspecialchars($result['total_points']); ?></td>
                <td><?= htmlspecialchars($result['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Back to Top Button -->
<button id="backToTopBtn" class="btn btn-primary" onclick="scrollToTop()">Back to Top</button>


            </div>
        <?php endif; ?>
    </div>
</div>
<script>// Show or hide the "Back to Top" button
window.onscroll = function() {
    let btn = document.getElementById("backToTopBtn");
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        btn.style.display = "block"; // Show button when scrolled 20px or more
    } else {
        btn.style.display = "none"; // Hide button when at top
    }
};

// Scroll to top function
function scrollToTop() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
</script>
<!-- Include necessary JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
