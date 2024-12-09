<?php
// Include database connection
require_once 'config.php'; // Ensure this file contains the $pdo connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_message = trim($_POST['feedback']);

    // Validate feedback message
    if (!empty($feedback_message)) {
        try {
            // Prepare and execute the SQL insert statement
            $stmt = $pdo->prepare("INSERT INTO randomfeedback (message) VALUES (:message)");
            $stmt->execute([':message' => $feedback_message]);

            // Success message
            $success_message = "Your feedback has been successfully submitted!";
        } catch (PDOException $e) {
            // Error handling
            $error_message = "Error saving feedback: " . $e->getMessage();
        }
    } else {
        $error_message = "Feedback cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #2196f3, #6ec6ff); /* Gradient background */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

.container {
    background: #ffffff; /* White background for the form */
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); /* Deeper shadow for a floating effect */
    width: 100%;
    max-width: 500px;
    animation: fadeIn 0.5s ease-in-out; /* Smooth fade-in animation */
}

h1 {
    text-align: center;
    color: #1e88e5; /* Cool blue for header */
    margin-bottom: 25px;
    font-size: 28px;
    font-weight: bold;
}

.form-group {
    margin-bottom: 25px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 10px;
    color: #1565c0; /* Deeper blue for label text */
    font-size: 14px;
}

textarea {
    width: 95%;
    padding: 15px;
    border: 1px solid #bbdefb; /* Subtle blue border */
    border-radius: 8px;
    font-size: 16px;
    resize: none;
    height: 120px;
    transition: box-shadow 0.3s ease, border-color 0.3s ease;
}

textarea:focus {
    outline: none;
    border-color: #1e88e5; /* Focus border */
    box-shadow: 0 0 8px rgba(33, 150, 243, 0.5); /* Blue glow */
}

button {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #1e88e5, #1565c0); /* Gradient for button */
    color: #ffffff; /* White text */
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.3s ease, background 0.3s ease;
}

button:hover {
    background: linear-gradient(135deg, #42a5f5, #1e88e5); /* Brighter gradient on hover */
    transform: scale(1.05); /* Slightly enlarge the button */
}

.back-button {
    margin-top: 15px;
    background: linear-gradient(135deg, #90caf9, #64b5f6); /* Lighter gradient for back button */
}

.back-button:hover {
    background: linear-gradient(135deg, #64b5f6, #42a5f5); /* Slightly darker on hover */
}

.message {
    margin-top: 20px;
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    padding: 10px;
    border-radius: 8px;
}

.success {
    color: #2e7d32; /* Green for success */
    background-color: #e8f5e9; /* Light green background */
}

.error {
    color: #b71c1c; /* Red for errors */
    background-color: #ffebee; /* Light red background */
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Feedback</h1>
        <?php if (isset($success_message)): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="message error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form action="randomfeedback.php" method="POST">
            <div class="form-group">
                <label for="feedback">Your Feedback:</label>
                <textarea name="feedback" id="feedback" placeholder="Write your feedback here..." required></textarea>
            </div>
            <button type="submit">Submit</button>
        </form>
        <form action="index.php" method="GET">
            <button type="submit" class="back-button">Back</button>
        </form>
    </div>
</body>
</html>
