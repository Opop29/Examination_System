    <?php
    session_start();

    // Check if the admin is logged in
    if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
        header("location: index.php");
        exit;
    }

    require_once "config.php";

    // Initialize variables
    $exam_title = $course = $points_per_question = $year_level = "";
    $num_questions = $num_mcq = $num_tf = $num_fill = 0;
    $step = 1; // Step 1: Create exam; Step 2: Fill up questions
    $questions_data = [];
    $error_msg = $success_msg = "";

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['step']) && $_POST['step'] == 1) {
            // Step 1: Save exam details
            $exam_title = trim($_POST["exam_title"]);
            $course = trim($_POST["course"]);
            $points_per_question = intval(trim($_POST["points_per_question"]));
            $year_level = trim($_POST["year_level"]);
            $num_questions = intval(trim($_POST["num_questions"]));
            $num_mcq = intval(trim($_POST["num_mcq"]));
            $num_tf = intval(trim($_POST["num_tf"]));
            $num_fill = intval(trim($_POST["num_fill"]));

            // Validate input
            if (
                empty($exam_title) || empty($course) || $points_per_question <= 0 ||
                $num_questions <= 0 || empty($year_level) ||
                ($num_mcq + $num_tf + $num_fill) !== $num_questions
            ) {
                $error_msg = "All fields are required, and question types must add up to the total number of questions.";
            } else {
                try {
                    // Insert exam metadata into the database
                    $sql = "INSERT INTO exam_meta (exam_title, course, points_per_question, year_level, num_questions) 
                            VALUES (:exam_title, :course, :points_per_question, :year_level, :num_questions)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":exam_title", $exam_title, PDO::PARAM_STR);
                    $stmt->bindParam(":course", $course, PDO::PARAM_STR);
                    $stmt->bindParam(":points_per_question", $points_per_question, PDO::PARAM_INT);
                    $stmt->bindParam(":year_level", $year_level, PDO::PARAM_STR);
                    $stmt->bindParam(":num_questions", $num_questions, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        $_SESSION['exam_id'] = $pdo->lastInsertId(); // Store exam ID in session
                        $_SESSION['question_plan'] = [
                            'mcq' => $num_mcq,
                            'tf' => $num_tf,
                            'fill' => $num_fill
                        ];
                        $step = 2; // Proceed to Step 2: Fill up questions
                    } else {
                        $error_msg = "Failed to save exam details. Please try again.";
                    }
                } catch (PDOException $e) {
                    $error_msg = "Database error: " . $e->getMessage();
                }
            }
        } elseif (isset($_POST['step']) && $_POST['step'] == 2) {
            // Step 2: Save questions
            $exam_id = $_SESSION['exam_id'];
            $question_plan = $_SESSION['question_plan'];
            $questions_data = $_POST['questions'];

        try {
        foreach ($questions_data as $question) {
            $options = null; // Default for non-MCQ questions
            if ($question['type'] === 'mcq') {
                // Convert options array to JSON
                $options = json_encode($question['options']);
            }

            $sql = "INSERT INTO exam_questions (exam_id, question_text, question_type, options, correct_answer) 
                    VALUES (:exam_id, :question_text, :question_type, :options, :correct_answer)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":exam_id", $exam_id, PDO::PARAM_INT);
            $stmt->bindParam(":question_text", $question['text'], PDO::PARAM_STR);
            $stmt->bindParam(":question_type", $question['type'], PDO::PARAM_STR);
            $stmt->bindParam(":options", $options, PDO::PARAM_STR);
            $stmt->bindParam(":correct_answer", $question['correct'], PDO::PARAM_STR);
            $stmt->execute();
        }

        $success_msg = "Questions saved successfully!";
        unset($_SESSION['exam_id'], $_SESSION['question_plan']);
        $step = 1; // Reset to Step 1
    } catch (PDOException $e) {
        $error_msg = "Failed to save questions: " . $e->getMessage();
    }

        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Exam</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="navigation.css">

    <style>
        h2.text-center {
        color: #007bff; /* Cool blue color */
        font-weight: 700; /* Bold */
        text-transform: uppercase; /* Optional: To make it more striking */
        letter-spacing: 2px; /* Optional: Adds space between letters for a stylish look */
        margin-bottom: 20px; /* Adjust bottom margin as needed */
    }

        .navbar .dropdown-menu {
        background-color: #343a40; /* Matches the dark navbar background */
        border: none;
    }
    .navbar .dropdown-item {
        color: #fff;
    }
    .navbar .dropdown-item:hover {
        background-color: #495057; /* Slightly lighter shade for hover effect */
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


        <!-- Main Content -->
        <div class="container mt-4">
            <h2 class="text-center mb-4">Create a New Exam</h2>

            <!-- Display Messages -->
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success"><?php echo $success_msg; ?></div>
            <?php endif; ?>

            <!-- Step 1: Create Exam -->
            <?php if ($step == 1): ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="step" value="1">
                    <div class="form-group">
                        <label for="exam_title">Exam Title</label>
                        <input type="text" name="exam_title" id="exam_title" class="form-control" value="<?php echo htmlspecialchars($exam_title); ?>">
                    </div>
                    <div class="form-group">
    <label for="course">Course</label>
    <select name="course" id="course" class="form-control">
        <option value="all course" <?php echo ($course == "all course") ? 'selected' : ''; ?>>All Course</option>
        <option value="Information Technology" <?php echo ($course == "Information Technology") ? 'selected' : ''; ?>>Information Technology</option>
        <option value="BA (Business Administration)" <?php echo ($course == "BA (Business Administration)") ? 'selected' : ''; ?>>BA (Business Administration)</option>
        <option value="TEP (Teacher Education Program)" <?php echo ($course == "TEP (Teacher Education Program)") ? 'selected' : ''; ?>>TEP (Teacher Education Program)</option>
    </select>
</div>

                    <div class="form-group">
                        <label for="points_per_question">Points per Correct Answer</label>
                        <input type="number" name="points_per_question" id="points_per_question" class="form-control" value="<?php echo htmlspecialchars($points_per_question); ?>" min="1">
                    </div>
                    <div class="form-group">
    <label for="year_level">Year Level</label>
    <select name="year_level" id="year_level" class="form-control">
        <option value="all level" <?php echo ($year_level === "all level" ? "selected" : ""); ?>>All Level</option>
        <option value="1st Year" <?php echo ($year_level === "1st Year" ? "selected" : ""); ?>>1st Year</option>
        <option value="2nd Year" <?php echo ($year_level === "2nd Year" ? "selected" : ""); ?>>2nd Year</option>
        <option value="3rd Year" <?php echo ($year_level === "3rd Year" ? "selected" : ""); ?>>3rd Year</option>
        <option value="4th Year" <?php echo ($year_level === "4th Year" ? "selected" : ""); ?>>4th Year</option>
    </select>
</div>

                    <div class="form-group">
                        <label for="num_questions">Number of Questions</label>
                        <input type="number" name="num_questions" id="num_questions" class="form-control" value="<?php echo htmlspecialchars($num_questions); ?>" min="1">
                    </div>
                    <div class="form-group">
                        <label for="num_mcq">Number of Multiple Choice Questions</label>
                        <input type="number" name="num_mcq" id="num_mcq" class="form-control" value="<?php echo htmlspecialchars($num_mcq); ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label for="num_tf">Number of True/False Questions</label>
                        <input type="number" name="num_tf" id="num_tf" class="form-control" value="<?php echo htmlspecialchars($num_tf); ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label for="num_fill">Number of Fill-in-the-Blank Questions</label>
                        <input type="number" name="num_fill" id="num_fill" class="form-control" value="<?php echo htmlspecialchars($num_fill); ?>" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary">Proceed to Create Questions</button>
                </form>
            <?php endif; ?>

            <!-- Step 2: Fill Up Questions -->
            <?php if ($step == 2): ?>
        <h3 class="text-center">Step 2: Add Questions</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="step" value="2">
            <?php
            $question_number = 1;
            foreach ($_SESSION['question_plan'] as $type => $count) {
                for ($i = 0; $i < $count; $i++) {
                    ?>
                    <div class="form-group border p-3 mb-3">
                        <h5>Question <?php echo $question_number; ?>: 
                            <span class="badge badge-primary"><?php echo strtoupper($type); ?></span>
                        </h5>
                        <label for="questions[<?php echo $question_number; ?>][text]">Question Text</label>
                        <input type="text" name="questions[<?php echo $question_number; ?>][text]" class="form-control mb-2" required>

                        <input type="hidden" name="questions[<?php echo $question_number; ?>][type]" value="<?php echo $type; ?>">
                        
                        <?php if ($type === 'mcq'): ?>
                            <!-- Multiple Choice Options -->
                            <label>Number of Choices</label>
                            <select class="form-control mb-3" id="num_choices_<?php echo $question_number; ?>" onchange="updateChoices(<?php echo $question_number; ?>)" required>
                                <option value="" disabled selected>Select number of choices</option>
                                <?php for ($n = 2; $n <= 5; $n++): ?>
                                    <option value="<?php echo $n; ?>"><?php echo $n; ?> Choices</option>
                                <?php endfor; ?>
                            </select>
                            
                            <div id="choices_container_<?php echo $question_number; ?>" class="mt-3"></div>
                            
                            <label>Correct Answer</label>
                            <select name="questions[<?php echo $question_number; ?>][correct]" id="correct_<?php echo $question_number; ?>" class="form-control" required>
                                <option value="" disabled selected>Select the correct answer</option>
                            </select>
                        <?php elseif ($type === 'tf'): ?>
                            <!-- True/False -->
                            <label>Correct Answer</label>
                            <select name="questions[<?php echo $question_number; ?>][correct]" class="form-control" required>
                                <option value="True">True</option>
                                <option value="False">False</option>
                            </select>
                        <?php elseif ($type === 'fill'): ?>
                            <!-- Fill in the Blank -->
                            <label>Correct Answer</label>
                            <input type="text" name="questions[<?php echo $question_number; ?>][correct]" class="form-control" required>
                        <?php endif; ?>
                    </div>
                    <?php
                    $question_number++;
                }
            }
            ?>
            <button type="submit" class="btn btn-success">Save Questions</button>
        </form>

        <!-- Back Button -->
        <a href="addexam.php" class="btn btn-secondary mt-3">Back</a>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- JavaScript for Dynamic Choice Generation -->
        <script>

            // Toggle visibility of sections
            function toggleSection(sectionId) {
                const sections = document.querySelectorAll('.section');
                sections.forEach(section => {
                    section.style.display = section.id === sectionId ? 'block' : 'none';
                });
            }

            function updateChoices(questionNumber) {
                const numChoices = document.getElementById(`num_choices_${questionNumber}`).value;
                const choicesContainer = document.getElementById(`choices_container_${questionNumber}`);
                const correctSelect = document.getElementById(`correct_${questionNumber}`);
                
                // Clear existing options
                choicesContainer.innerHTML = "";
                correctSelect.innerHTML = '<option value="" disabled selected>Select the correct answer</option>';
                
                for (let i = 0; i < numChoices; i++) {
                    const letter = String.fromCharCode(65 + i); // A, B, C, etc.
                    const inputName = `questions[${questionNumber}][options][${letter}]`;

                    // Create input field for each choice
                    const inputGroup = document.createElement("div");
                    inputGroup.classList.add("form-group", "mb-2");

                    inputGroup.innerHTML = `
                        <label for="${inputName}">Choice ${letter}</label>
                        <input type="text" name="${inputName}" id="${inputName}" class="form-control" required>
                    `;
                    choicesContainer.appendChild(inputGroup);

                    // Add option to Correct Answer select
                    const option = document.createElement("option");
                    option.value = letter;
                    option.textContent = `Choice ${letter}`;
                    correctSelect.appendChild(option);
                }
            }
            
        </script>
    

    <?php endif; ?>


        </div>
    </body>
    </html>
