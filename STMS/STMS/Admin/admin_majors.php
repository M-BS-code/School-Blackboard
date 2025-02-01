<?php
session_start();
include('../includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: AdminSignup.php");
    exit;
}

// Function to fetch all majors from the database
function getAllMajors() {
    global $conn;
    $sql = "SELECT * FROM major";
    $result = $conn->query($sql);
    $majors = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $majors[] = $row;
        }
    }
    return $majors;
}

// Fetch all majors
$majors = getAllMajors();

// Define variables to hold form input
$major_id = $major_name = $major_year = $major_description = '';
$error = '';

// Handle form submission for adding/editing major
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_major'])) {
        // Add Major
        $major_name = $_POST['major_name'];
        $major_year = $_POST['major_year'];
        $major_description = $_POST['major_description'];

        // Validate form data
        if (empty($major_name) || empty($major_year)) {
            $error = "Major name and year are required.";
        } else {
            // Insert major into database
            $sql = "INSERT INTO major (major_name, major_year, major_description) VALUES ('$major_name', '$major_year', '$major_description')";
            if ($conn->query($sql) === TRUE) {
                // Redirect to refresh the page
                header("Location: admin_majors.php");
                exit;
            } else {
                $error = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['edit_major'])) {
        // Edit Major
        $major_id = $_POST['major_id'];
        $major_name = $_POST['major_name'];
        $major_year = $_POST['major_year'];
        $major_description = $_POST['major_description'];

        // Validate form data
        if (empty($major_name) || empty($major_year)) {
            $error = "Major name and year are required.";
        } else {
            // Update major in database
            $sql = "UPDATE major SET major_name='$major_name', major_year='$major_year', major_description='$major_description' WHERE major_id=$major_id";
            if ($conn->query($sql) === TRUE) {
                // Redirect to refresh the page
                header("Location: admin_majors.php");
                exit;
            } else {
                $error = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['delete_major'])) {
        // Delete Major
        $major_id = $_POST['major_id'];

        // Delete major from database
        $sql = "DELETE FROM major WHERE major_id=$major_id";
        if ($conn->query($sql) === TRUE) {
            // Redirect to refresh the page
            header("Location: admin_majors.php");
            exit;
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// If editing, retrieve major details
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM major WHERE major_id=$edit_id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $major_id = $row['major_id'];
        $major_name = $row['major_name'];
        $major_year = $row['major_year'];
        $major_description = $row['major_description'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Major</title>
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
    

    .table thead th, 
    .table tbody tr td { 
        color: #fff !important; 
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
            margin-right: 650px; 
            justify-content: center;
        }
 
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">  

            <div class="col-md-12 dashboard-header">
            <?php include('AdminNavbar.php'); ?>
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i>Adding Majors</h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">

                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="major_id" value="<?php echo $major_id; ?>">
                            <div class="form-group">
                                <label for="major_name">Major Name:</label>
                                <input type="text" class="form-control" id="major_name" name="major_name" value="<?php echo $major_name; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="major_year">Major Year:</label>
                                <input type="text" class="form-control" id="major_year" name="major_year" value="<?php echo $major_year; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="major_description">Major Description:</label>
                                <textarea class="form-control" id="major_description" name="major_description"><?php echo $major_description; ?></textarea>
                            </div>
                            <?php if(empty($major_id)) { ?>
                                <button type="submit" class="btn btn-primary" name="add_major">Add Major</button>
                            <?php } else { ?>
                                <button type="submit" class="btn btn-success" name="edit_major">Save Changes</button>
                            <?php } ?>
                            <div class="text-danger mt-3"><?php echo $error; ?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h2>List of Majors</h2>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Major Name</th>
                                    <th>Year</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($majors as $major) { ?>
                                    <tr>
                                        <td><?php echo $major['major_name']; ?></td>
                                        <td><?php echo $major['major_year']; ?></td>
                                        <td><?php echo $major['major_description']; ?></td>
                                        <td>
                                            <a href="admin_majors.php?edit_id=<?php echo $major['major_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <form method="post" style="display: inline-block;">
                                                <input type="hidden" name="major_id" value="<?php echo $major['major_id']; ?>">
                                                <button type="submit" name="delete_major" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this major?');">Delete</button>
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
