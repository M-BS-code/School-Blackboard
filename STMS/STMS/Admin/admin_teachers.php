<?php
session_start();
include('../includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: AdminSignup.php");
    exit;
}

// Function to fetch all teachers from the database
function getAllTeachers() {
    global $conn;
    $sql = "SELECT * FROM Users WHERE role='teacher'";
    $result = $conn->query($sql);
    $teachers = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
    }
    return $teachers;
}

// Fetch all teachers
$teachers = getAllTeachers();

// Update mode variables
$update_mode = false;
$update_teacher_id = '';
$update_teacher_username = '';
$update_teacher_email = '';
$update_teacher_password = '';

// Process Add or Update Teacher
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_teacher'])) {
        // Add Teacher
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = 'teacher';

        // Insert teacher data into Users table
        $sql = "INSERT INTO Users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_teachers.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update_teacher'])) {
        // Update mode
        $update_mode = true;
        $update_teacher_id = $_POST['teacher_id'];
        $update_teacher_username = $_POST['username'];
        $update_teacher_email = $_POST['email'];
        $update_teacher_password = $_POST['password'];
    } elseif (isset($_POST['save_update'])) {
        // Save Update
        $teacher_id = $_POST['teacher_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Update teacher data in Users table
        $sql = "UPDATE Users SET username='$username', email='$email', password = '$password' WHERE user_id=$teacher_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_teachers.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Delete Teacher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_teacher'])) {
    $teacher_id = $_POST['teacher_id'];

    // Delete teacher data from Users table
    $sql = "DELETE FROM Users WHERE user_id=$teacher_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_teachers.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Teachers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 8px 15px 20px 25px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.2);
    }

    .table thead th,
    .table tbody tr td { 
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
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i>Admin Teachers</h1>
            </div>
        </div>
    </div>

        <!-- Add or Update Teacher Form -->
        <div class="card">
            <div class="card-body">
                <h2><?php echo $update_mode ? 'Update Teacher' : 'Add Teacher'; ?></h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php if($update_mode) { ?>
                        <input type="hidden" name="teacher_id" value="<?php echo $update_teacher_id; ?>">
                    <?php } ?>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $update_mode ? $update_teacher_username : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $update_mode ? $update_teacher_email : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" name="password" value="<?php echo $update_mode ? $update_teacher_password : ''; ?>" required>
                    </div>
                    <?php if(!$update_mode) { ?>
                        <button type="submit" class="btn btn-primary" name="add_teacher">Add Teacher</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-primary" name="save_update">Save Update</button>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- List of Teachers -->
        <div class="card mt-4">
            <div class="card-body">
                <h2>Teachers</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($teachers as $teacher) { ?>
                            <tr>
                                <td><?php echo $teacher['username']; ?></td>
                                <td><?php echo $teacher['email']; ?></td>
                                <td><?php echo $teacher['password']; ?></td>
                                <td>
                                    <!-- Update Teacher Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="teacher_id" value="<?php echo $teacher['user_id']; ?>">
                                        <input type="hidden" name="username" value="<?php echo $teacher['username']; ?>">
                                        <input type="hidden" name="email" value="<?php echo $teacher['email']; ?>">
                                        <input type="hidden" name="password" value="<?php echo $teacher['password']; ?>">
                                        <button type="submit" class="btn btn-info btn-sm" name="update_teacher">Update</button>
                                    </form>
                                    <!-- Delete Teacher Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                        <input type="hidden" name="teacher_id" value="<?php echo $teacher['user_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="delete_teacher">Delete</button>
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
