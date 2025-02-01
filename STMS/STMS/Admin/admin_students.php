<?php
session_start();
include('../includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: AdminSignup.php");
    exit;
}

// Function to fetch all students from the database
function getAllStudents() {
    global $conn;
    $sql = "SELECT * FROM Users WHERE role='student'";
    $result = $conn->query($sql);
    $students = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    return $students;
}

// Fetch all students
$students = getAllStudents();

// Update mode variables
$update_mode = false;
$update_student_id = '';
$update_student_username = '';
$update_student_email = '';

// Process Add or Update Student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_student'])) {
        // Add Student
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = 'student';

        // Insert student data into Users table
        $sql = "INSERT INTO Users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_students.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update_student'])) {
        // Update mode
        $update_mode = true;
        $update_student_id = $_POST['student_id'];
        $update_student_username = $_POST['username'];
        $update_student_email = $_POST['email'];
        $update_student_password = $_POST['password'];
    } elseif (isset($_POST['save_update'])) {
        // Save Update
        $student_id = $_POST['student_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Update student data in Users table
        $sql = "UPDATE Users SET username='$username', email='$email', password = '$password' WHERE user_id=$student_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_students.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Delete Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $student_id = $_POST['student_id'];

    // Delete student data from Users table
    $sql = "DELETE FROM Users WHERE user_id=$student_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_students.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet"href="../css/styles.css">
    <title>Admin Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Custom CSS file -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 8px 15px 20px 25px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.2);
    }
    
    .table-striped tbody tr td, 
    .table-striped tbody tr th,
    .table thead th {
        color: #fff !important;
    }
    h1 {
        text-align: center;
    }
    :root {
            --white-color: #fff;
            --paraText-color: #777;
            --heading-color: #333;
            --primary-color: rgb(31, 153, 167);
            --secondary-color: rgb(94, 7, 40);
        }
        .dashboard-header {
            background-color: var(--secondary-color);
            padding: 20px;
            display: flex;
            justify-content: space-between; 
            align-items: center; 
        }

        .menu-icon {
            font-size: 24px; 
            cursor: pointer; 
            align-items: center; 
            

        }

        .dashboard-title {
            color: white;
            display: flex;
            align-items: center; 
            margin-right: 600px; 
            justify-content: center;
        }   
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">  

            <div class="col-md-12 dashboard-header">
            <?php include('AdminNavbar.php'); ?>
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i>Admin Students</h1>
            </div>
        </div>
    </div>
    
        <div class="card mb-4">
        
            <div class="card-body">
                <h2><?php echo $update_mode ? 'Update Student' : 'Add Student'; ?></h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php if($update_mode) { ?>
                        <input type="hidden" name="student_id" value="<?php echo $update_student_id; ?>">
                    <?php } ?>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $update_mode ? $update_student_username : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $update_mode ? $update_student_email : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" name="password" value="<?php echo $update_mode ? $update_student_password : ''; ?>" required>
                    </div>
                    <?php if(!$update_mode) { ?>
                        <button type="submit" class="btn btn-primary" name="add_student">Add Student</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-primary" name="save_update">Save Update</button>
                    <?php } ?>
                </form>
            </div>
        </div>
    
        <!-- List of Students -->
        <div class="card">
            <div class="card-body">
                <h2>Students</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student) { ?>
                            <tr>
                                <td><?php echo $student['username']; ?></td>
                                <td><?php echo $student['email']; ?></td>
                                <td><?php echo $student['password']; ?></td>
                                <td>
                                    <!-- Update Student Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="student_id" value="<?php echo $student['user_id']; ?>">
                                        <input type="hidden" name="username" value="<?php echo $student['username']; ?>">
                                        <input type="hidden" name="email" value="<?php echo $student['email']; ?>">
                                        <input type="hidden" name="password" value="<?php echo $student['password']; ?>">
                                        <button type="submit" class="btn btn-info btn-sm" name="update_student">Update</button>
                                    </form>
                                    <!-- Delete Student Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        <input type="hidden" name="student_id" value="<?php echo $student['user_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="delete_student">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    var content = document.querySelector(".content");

    if (sidebar.style.width === "250px") {
        sidebar.style.width = "0";
        content.style.marginLeft = "0";
    } else {
        sidebar.style.width = "250px";
        content.style.marginLeft = "250px";
    }
}

function closeNav() {
    var sidebar = document.getElementById("sidebar");
    var content = document.querySelector(".content");

    sidebar.style.width = "0";
    content.style.marginLeft = "0";
}
    </script>
</body>
</html>
