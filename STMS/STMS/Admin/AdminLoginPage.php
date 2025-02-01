<?php
session_start();
// Database connection code here
include('../includes/config.php');
$stmt = $conn->prepare("SELECT * FROM Users WHERE username=? AND role='admin'");
$stmt->bind_param("s", $username); // "s" indicates the type is a string
$stmt->execute();
$result = $stmt->get_result();

// Admin Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve admin data from Users table based on username
    $sql = "SELECT * FROM Users WHERE username='$username' AND role='admin'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid password";
        }
    } else {
        $_SESSION['login_error'] = "Admin not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login</title>
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
            <h1>Welcome to Admin Portal</h1>
            <p>Login to access your account</p>
        </div>
        <div class="login-section">
            <?php
                        // Check if login error exists
                        if (isset($_SESSION['login_error'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['login_error'] . '</div>';
                            unset($_SESSION['login_error']); // Remove the login error message from session
                        }
                        ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">    
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="admin_login" class="btn btn-primary">Login</button>
                <div class="footer-links">
                    <p>Don't have an account? Register <a href="AdminSignup.php">here</a></p>
                </div>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
