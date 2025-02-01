<?php
session_start();
include('../includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: AdminSignup.php");
    exit;
}

// Function to fetch all admins from the database
function getAllAdmins() {
    global $conn;
    $sql = "SELECT * FROM Users WHERE role='admin'";
    $result = $conn->query($sql);
    $admins = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
    }
    return $admins;
}

// Fetch all admins
$admins = getAllAdmins();

// Update mode variables
$update_mode = false;
$update_admin_id = '';
$update_admin_username = '';
$update_admin_email = '';
$update_admin_password = '';

// Process Add or Update Admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_admin'])) {
        // Add Admin
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = 'admin';

        // Insert admin data into Users table
        $sql = "INSERT INTO Users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_admins.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update_admin'])) {
        // Update mode
        $update_mode = true;
        $update_admin_id = $_POST['admin_id'];
        $update_admin_username = $_POST['username'];
        $update_admin_email = $_POST['email'];
        $update_admin_password = $_POST['password'];
    } elseif (isset($_POST['save_update'])) {
        // Save Update
        $admin_id = $_POST['admin_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Update admin data in Users table
        $sql = "UPDATE Users SET username='$username', email='$email', password = '$password' WHERE user_id=$admin_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_admins.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Delete Admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_admin'])) {
    $admin_id = $_POST['admin_id'];

    // Delete admin data from Users table
    $sql = "DELETE FROM Users WHERE user_id=$admin_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_admins.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Admins</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    
    /* Styles for table text color */
    .table thead th, /* Header cells */
    .table tbody tr td { /* Data cells */
        color: #fff !important; /* Make text white */
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
            justify-content: space-between; /* Space content evenly */
            align-items: center; /* Center content vertically */
        }

        .menu-icon {
            font-size: 24px; /* Adjust the size of the menu icon */
            cursor: pointer; /* Show pointer cursor on hover */
            align-items: center; /* Center content vertically */
            

        }

        .dashboard-title {
            color: white; /* Set the color of the title */
            display: flex; /* Use flexbox */
            align-items: center; /* Center content vertically */
            margin-right: 600px; /* Push the title to the right */
        }   
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">  
        
        
            <div class="col-md-12 dashboard-header">
            <?php include('AdminNavbar.php'); ?>
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i> Admin Admins</h1>
            </div>
        </div>
    </div>
    <div class="content">

        <div class="card">
            <div class="card-body">
                <h2><?php echo $update_mode ? 'Update Admin' : 'Add Admin'; ?></h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php if($update_mode) { ?>
                        <input type="hidden" name="admin_id" value="<?php echo $update_admin_id; ?>">
                    <?php } ?>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $update_mode ? $update_admin_username : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $update_mode ? $update_admin_email : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" name="password" value="<?php echo $update_mode ? $update_admin_password : ''; ?>" required>
                    </div>
                    <?php if(!$update_mode) { ?>
                        <button type="submit" class="btn btn-primary" name="add_admin">Add Admin</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-primary" name="save_update">Save Update</button>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- List of Admins -->
        <div class="card mt-4">
            <div class="card-body">
                <h2>Admins</h2>
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
                            <?php foreach ($admins as $admin) { ?>
                            <tr>
                                <td><?php echo $admin['username']; ?></td>
                                <td><?php echo $admin['email']; ?></td>
                                <td><?php echo $admin['password']; ?></td>
                                <td>
                                    <!-- Update Admin Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="admin_id" value="<?php echo $admin['user_id']; ?>">
                                        <input type="hidden" name="username" value="<?php echo $admin['username']; ?>">
                                        <input type="hidden" name="email" value="<?php echo $admin['email']; ?>">
                                        <input type="hidden" name="password" value="<?php echo $admin['password']; ?>">
                                        <button type="submit" class="btn btn-info btn-sm" name="update_admin">Update</button>
                                    </form>
                                    <!-- Delete Admin Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                        <input type="hidden" name="admin_id" value="<?php echo $admin['user_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="delete_admin">Delete</button>
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
