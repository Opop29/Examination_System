<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}

require_once "config.php";

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['admin_login'])) { // Handle admin login
        $admin_email = trim($_POST['admin_email']);
        $admin_password = trim($_POST['admin_password']);

        $sql = "SELECT id, username, password FROM admin WHERE username = :username";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = $admin_email;

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];

                        if (password_verify($admin_password, $hashed_password)) {
                            // Admin Login Successful
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = "admin";

                            header("location: admin_dashboard.php");
                            exit;
                        } else {
                            $login_err = "Invalid admin email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid admin email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    } else { // Handle user login
        if (empty(trim($_POST["email"]))) {
            $email_err = "Please enter email.";
        } else {
            $email = trim($_POST["email"]);
        }

        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        if (empty($email_err) && empty($password_err)) {
            $sql = "SELECT id, email, password FROM users WHERE email = :email";

            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                $param_email = trim($_POST["email"]);

                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        if ($row = $stmt->fetch()) {
                            $id = $row["id"];
                            $email = $row["email"];
                            $hashed_password = $row["password"];
                            if (password_verify($password, $hashed_password)) {
                                // User Login Successful
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;

                                header("location: dashboard.php");
                                exit;
                            } else {
                                $login_err = "Invalid email or password.";
                            }
                        }
                    } else {
                        $login_err = "Invalid email or password.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                unset($stmt);
            }
        }
        unset($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
<h1 >Online Examination System</h1>

    <a class="nav-link text-white font-weight-bold" href="randomfeedback.php" style="margin-left: 60px;">Share Feedback</a>

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

<div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>

    <?php 
    if (!empty($login_err)) {
        echo '<div class="alert alert-danger" id="loginError">' . $login_err . '</div>';
    }        
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>    
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php" style="color: #ccc;">Sign up now</a>.</p>
    </form>
</div>

<footer>
    <div class="left-section">
        <p><span>System Name:</span> Login System</p>
        <p><span>Group Name:</span> Group 1: Joshua T. Quidit, Alladen Cagubcub, Rushil Hinoyog</p>
        <p><span>Section Code:</span> IT56</p>
    </div>
    <div class="right-section">
        <p><span>College Name:</span> Northern Bukidnon State College</p>
        <p><span>Subject Name:</span> System Integration Architecture</p>
        <p><span>Instructor:</span> Dr. John Doe</p>
    </div>
</footer>
<script>
    const adminModal = document.getElementById("adminModal");
    const adminLoginButton = document.getElementById("adminLoginButton");

    adminLoginButton.onclick = () => {
        adminModal.style.display = "block";
    };

    function closeModal() {
        adminModal.style.display = "none";
    }

    window.onclick = (event) => {
        if (event.target === adminModal) {
            closeModal();
        }
    };

    const loginError = document.getElementById("loginError");
    if (loginError) {
        setTimeout(() => {
            loginError.style.opacity = "0"; // Start fading out
            setTimeout(() => {
                loginError.style.display = "none"; // Fully hide after fade
            }, 500); // Wait for the fade-out transition
        }, 2000); // 2 seconds before fade starts
    }
</script>

</body>
</html>
