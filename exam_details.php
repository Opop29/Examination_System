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
$user_id = $_SESSION['id'];  // Assuming session contains the logged-in user's ID

// Check if the user has already taken this exam
$sql = "SELECT * FROM exam_results WHERE user_id = :user_id AND exam_id = :exam_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // If the user already has a result for this exam, redirect to the results page
    header("Location: exam_results.php?exam_id=$exam_id");
    exit;
}

// Fetch the exam meta details to get points per question
$sql = "SELECT points_per_question, num_questions FROM exam_meta WHERE id = :exam_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
$stmt->execute();
$exam_meta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam_meta) {
    echo "Invalid exam details.";
    exit;
}

$points_per_question = $exam_meta['points_per_question'];
$total_questions = $exam_meta['num_questions'];

// Fetch questions for the exam
$sql = "SELECT * FROM exam_questions WHERE exam_id = :exam_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions) {
    echo "No questions found for this exam.";
    exit;
}

// Initialize variables
$score = 0;
$correct_answers = 0;
$wrong_answers = 0;
$show_results = false; // Flag to control the result display

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize an array to store the user's answers
    $user_answers = [];

    foreach ($questions as $question) {
        $question_id = $question['id'];
        $correct_answer = $question['correct_answer'];
        $user_answer = isset($_POST['question_' . $question_id]) ? $_POST['question_' . $question_id] : null;

        // Update score and correctness counts based on question type
        if ($question['question_type'] === 'mcq') {
            if ($user_answer === $correct_answer) {
                $score += $points_per_question;  // Add points for correct answer
                $correct_answers++;
            } else {
                $wrong_answers++;
            }
        } elseif ($question['question_type'] === 'tf') {
            if ($user_answer === $correct_answer) {
                $score += $points_per_question;  // Add points for correct answer
                $correct_answers++;
            } else {
                $wrong_answers++;
            }
        } elseif ($question['question_type'] === 'fill') {
            // Strip any extra spaces and compare case-insensitively
            if (strtolower(trim($user_answer)) === strtolower(trim($correct_answer))) {
                $score += $points_per_question;
                $correct_answers++;
            } else {
                $wrong_answers++;
            }
        }

        // Save the user's answer in the database (exam_answers table)
        $sql = "INSERT INTO exam_answers (user_id, question_id, answer) VALUES (:user_id, :question_id, :answer)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
        $stmt->bindParam(":answer", $user_answer, PDO::PARAM_STR);
        $stmt->execute();

        // Store the answer in the array for later use in the exam_result table
        $user_answers[] = [
            'question_id' => $question_id,
            'user_answer' => $user_answer,
            'correct_answer' => $correct_answer,
        ];
    }

    // Insert the exam result into the exam_results table
    $sql = "INSERT INTO exam_results (user_id, exam_id, total_correct, total_points, total_questions) 
            VALUES (:user_id, :exam_id, :correct_answers, :score, :total_questions)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
    $stmt->bindParam(":correct_answers", $correct_answers, PDO::PARAM_INT);
    $stmt->bindParam(":score", $score, PDO::PARAM_INT);
    $stmt->bindParam(":total_questions", $total_questions, PDO::PARAM_INT);
    $stmt->execute();

    // Set the flag to show results after form submission
    $show_results = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="exam_details.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
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
    <?php if ($show_results): ?>
        <!-- Exam Complete Section -->
        <div class="exam-complete">
            <h2>Exam Completed!</h2>
            <p><strong>Score:</strong> <?php echo $score; ?> / <?php echo $total_questions * $points_per_question; ?></p>
            <p><strong>Correct Answers:</strong> <?php echo $correct_answers; ?></p>
            <p><strong>Wrong Answers:</strong> <?php echo $wrong_answers; ?></p>
            <a href="exam_results.php?exam_id=<?php echo $exam_id; ?>" class="btn btn-primary">View Exam Results</a>
        </div>
    <?php else: ?>
        <!-- Exam Questions Form -->
        <div class="exam-container">
            <h2>Take Exam</h2>
            <form method="POST">
                <!-- Multiple Choice Questions Section -->
                <div class="question-section">
                    <h3 class="section-title">Multiple Choice Questions</h3>
                    <?php foreach ($questions as $question): ?>
                        <?php if ($question['question_type'] === 'mcq'): ?>
                            <div class="question mb-4">
                                <p><strong><?php echo htmlspecialchars($question['question_text']); ?></strong></p>
                                <?php 
                                $options = json_decode($question['options'], true);
                                foreach ($options as $key => $option): ?>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="question_<?php echo $question['id']; ?>" value="<?php echo $key; ?>" id="option_<?php echo $question['id'] . '_' . $key; ?>">
                                        <label class="form-check-label" for="option_<?php echo $question['id'] . '_' . $key; ?>">
                                            <?php echo htmlspecialchars($option); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

            <!-- Fill in the Blank Questions Section -->
            <div class="question-section">
                <h3 class="section-title">Fill in the Blank Questions</h3>
                <?php foreach ($questions as $question): ?>
                    <?php if ($question['question_type'] === 'fill'): ?>
                        <div class="question mb-4">
                            <p><strong><?php echo htmlspecialchars($question['question_text']); ?></strong></p>
                            <!-- Fill-in-the-blank input field -->
                            <div class="form-group">
                                <input type="text" class="form-control" name="question_<?php echo $question['id']; ?>" id="blank_<?php echo $question['id']; ?>" placeholder="Type your answer here">
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
<!-- True/False Questions Section -->
<div class="question-section">
    <h3 class="section-title">True/False Questions</h3>
    <?php foreach ($questions as $question): ?>
        <?php if ($question['question_type'] === 'tf'): ?>
            <div class="question mb-4">
                <p><strong><?php echo htmlspecialchars($question['question_text']); ?></strong></p>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="question_<?php echo $question['id']; ?>" value="True" id="true_<?php echo $question['id']; ?>">
                    <label class="form-check-label" for="true_<?php echo $question['id']; ?>">True</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="question_<?php echo $question['id']; ?>" value="False" id="false_<?php echo $question['id']; ?>">
                    <label class="form-check-label" for="false_<?php echo $question['id']; ?>">False</label>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-block">Submit Exam</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
