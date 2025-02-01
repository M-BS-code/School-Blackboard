<?php
session_start();
include('../includes/config.php');

// Check if student is already logged in, redirect to dashboard if logged in
if (isset($_SESSION['student_id'])) {
    header("Location: student_dashboard.php");
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Student_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check student credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND role = 'student'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            // Student authenticated, set session variables
            $_SESSION['student_id'] = $row['user_id'];
            $_SESSION['student_username'] = $row['username'];
            // Redirect to student dashboard
            header("Location: student_dashboard.php");
            exit;
        } else {
            $login_error = "Invalid username or password.";
        }
    } else {
        $login_error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/teacherLoginPage.css">
</head>
<body>
    <div class="area">
        <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
        </ul>
    </div>
    <div class="container">
        <div class="welcome-section">
            <h1>Welcome to Student Portal</h1>
            <p>Login to access your account</p>
        </div>
        <div class="login-section">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="Student_login">Login</button>

            </form>
            <?php if(isset($login_error)) echo "<div class='alert alert-danger mt-3'>$login_error</div>"; ?>
        </div>
    </div>
        <!-- Bootstrap JS and jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
