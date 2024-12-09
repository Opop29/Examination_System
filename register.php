<?php
require_once "config.php";

$username = $password = $confirm_password = $course = $year_level = $email = "";
$username_err = $password_err = $confirm_password_err = $course_err = $year_level_err = $email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);
            
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } elseif (!preg_match("/@nbsc\.edu\.ph$/", trim($_POST["email"]))) {
        $email_err = "Email must be from the domain @nbsc.edu.ph.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate course and year level
    if (empty(trim($_POST["course"]))) {
        $course_err = "Please select a course.";
    } else {
        $course = trim($_POST["course"]);
    }

    if (empty(trim($_POST["year_level"]))) {
        $year_level_err = "Please select your year level.";
    } else {
        $year_level = trim($_POST["year_level"]);
    }

    // Insert into database if no errors
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($course_err) && empty($year_level_err) && empty($email_err)) {
        $sql = "INSERT INTO users (username, email, password, course, year_level) VALUES (:username, :email, :password, :course, :year_level)";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":course", $param_course, PDO::PARAM_STR);
            $stmt->bindParam(":year_level", $param_year_level, PDO::PARAM_STR);
            
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_course = $course;
            $param_year_level = $year_level;
            
            if ($stmt->execute()) {
                header("location: index.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }
    
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('indexBG.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #003366;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
nav h1 {
    color: white;
    margin: 0;
    font-size: 20px; /* Reduce font size */
    white-space: nowrap; /* Prevent text wrapping to the next line */
    overflow: hidden; /* Optional: Ensures no overflow issues */
    text-overflow: ellipsis; /* Optional: Adds "..." if the text is too long */
    flex: 4; /* Allocates space for the text */
    max-width: 70%;
}


.admin-login-container {
    text-align: right;
    margin: 0; /* Remove margin to align with the navigation bar */
}

.admin-login-container button {
    margin-left: 10px; /* Space between buttons */
}
#adminLoginButton {
    background-color: #0056b3;
    color: white;
    padding: 5px 10px; /* Smaller padding for a compact button */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

#adminLoginButton:hover {
    background-color: #003d73;
    transform: translateY(-2px);
}

        .wrapper {
    width: 100%;
    max-width: 350px;
    padding: 30px;
    background-color: rgba(0, 51, 102, 0.9);
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    margin-top: 30px;
    margin-left: 50px; /* Adjusted to move the login container further left */
    position: relative; /* Ensures it stays in place relative to the page */
}


        h2 {
            text-align: center;
            color: #fff;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .form-control {
            background-color: #fff;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 8px rgba(0, 86, 179, 0.7);
        }

        .btn-primary {
            background-color: #0056b3;
            color: #fff;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #003d73;
            transform: translateY(-2px);
        }

        .alert-danger {
            background-color: #e60000;
            color: #fff;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: opacity 0.5s ease;
        }

        footer {
            width: 100%;
            background-color: #003366;
            color: #fff;
            display: flex;
            justify-content: space-between;
            padding: 15px;
            position: relative;
            bottom: 0;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        footer p {
            margin: 5px;
            font-size: 0.9rem;
        }

        footer p span {
            font-weight: bold;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #003366;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            width: 40%;
            color: white;
            text-align: center;
        }

        .close-button {
            color: white;
            float: right;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .close-button:hover {
            color: #ccc;
        }
    </style>
</head>
<body>
<nav>
    <h1>Online Examination System</h1>
    <div class="admin-login-container">
        <button id="adminLoginButton" class="btn btn-secondary">Admin Login</button>
    </div>
</nav>
<div id="adminModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="admin_email" class="form-control">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="admin_password" class="form-control">
            </div>
            <input type="hidden" name="admin_login" value="1">
            <button type="submit" class="btn btn-primary">Login as Admin</button>
        </form>
    </div>
</div>

    <!-- Form Container -->
    <div class="wrapper">
        <div class="form-container">
            <h2>Sign Up</h2>
            <p>Please fill this form to create an account.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
    <label>Course</label>
    <select name="course" class="form-control <?php echo (!empty($course_err)) ? 'is-invalid' : ''; ?>">
        <option value="">Select a course</option>
        <option value="Information Technology" <?php echo ($course == "Information Technology") ? 'selected' : ''; ?>>Information Technology</option>
        <option value="BA (Business Administration)" <?php echo ($course == "BA (Business Administration)") ? 'selected' : ''; ?>>BA (Business Administration)</option>
        <option value="TEP (Teacher Education Program)" <?php echo ($course == "TEP (Teacher Education Program)") ? 'selected' : ''; ?>>TEP (Teacher Education Program)</option>
    </select>
    <span class="invalid-feedback"><?php echo $course_err; ?></span>
</div>
<div class="form-group">
    <label>Year Level</label>
    <select name="year_level" class="form-control <?php echo (!empty($year_level_err)) ? 'is-invalid' : ''; ?>">
        <option value="">Select your year level</option>
        <option value="1st Year" <?php echo ($year_level == "1st Year") ? 'selected' : ''; ?>>1st Year</option>
        <option value="2nd Year" <?php echo ($year_level == "2nd Year") ? 'selected' : ''; ?>>2nd Year</option>
        <option value="3rd Year" <?php echo ($year_level == "3rd Year") ? 'selected' : ''; ?>>3rd Year</option>
        <option value="4th Year" <?php echo ($year_level == "4th Year") ? 'selected' : ''; ?>>4th Year</option>
    </select>
    <span class="invalid-feedback"><?php echo $year_level_err; ?></span>
</div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                <p>Already have an account? <a href="index.php">Login here</a>.</p>
            </form>
        </div>
    </div>
    <footer>
        <div class="left-section">
            <p><span>System Name:</span> Login System</p>
            <p><span>Group Name:</span> Group 1: Joshua T. Quidit, Alladen Cagubcub, Rushil Hinoyog</p>
            <p><span>section code:</span> it56</p>
        </div>
        <div class="right-section">
            <p><span>College Name:</span> Northern Bukidnon State College</p>
            <p><span>Subject Name:</span> System Integration Architecture</p>
            <p><span>Instructor:</span> Dr. John Doe</p>
        </div>
    </footer>
   
</body>
</html>
