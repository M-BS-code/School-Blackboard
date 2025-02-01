<?php
session_start();
include('../includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: AdminSignup.php");
    exit;
}

// Fetch all students
function getAllStudents() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role='student'";
    $result = $conn->query($sql);
    $students = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    return $students;
}

// Fetch all majors
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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign'])) {
    $student_id = $_POST['student_id'];
    $major_id = $_POST['major_id'];

    // Delete existing major assignments for this student
    $sql_delete = "DELETE FROM student_major WHERE student_id=$student_id";
    $conn->query($sql_delete);

    // Insert new major assignment for this student
    $sql_insert = "INSERT INTO student_major (student_id, major_id) VALUES ($student_id, $major_id)";
    $conn->query($sql_insert);

    // Redirect to refresh the page
    header("Location: admin_assign_major.php");
    exit;
}

// Get all students and majors
$students = getAllStudents();
$majors = getAllMajors();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Major to Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .card {
            margin-bottom: 20px;
        }
        
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 8px 15px 20px 25px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.2);
    }
    
    /* Styles to make all table text white */
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
            margin-right: 555px; /* Push the title to the right */
        }   
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">  
        
        
            <div class="col-md-12 dashboard-header">
            <?php include('AdminNavbar.php'); ?>
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i>Assign Major to Students</h1>
            </div>
        </div>
    </div> 
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>Assign Major to Students</h2>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="student">Select Student:</label>
                            <select class="form-control" id="student" name="student_id">
                                <?php foreach ($students as $student) { ?>
                                    <option value="<?php echo $student['user_id']; ?>"><?php echo $student['username']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="major">Select Major:</label>
                            <select class="form-control" id="major" name="major_id">
                                <?php foreach ($majors as $major) { ?>
                                    <option value="<?php echo $major['major_id']; ?>"><?php echo $major['major_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="assign">Assign</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Assigned Majors</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($students as $student) { ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><strong><?php echo $student['username']; ?></strong></h4>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Assigned Major</h5>
                                        <p class="card-text">
                                            <?php
                                            $student_id = $student['user_id'];
                                            $sql = "SELECT major_name FROM student_major JOIN major ON student_major.major_id = major.major_id WHERE student_major.student_id = $student_id";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                echo $row['major_name'];
                                            } else {
                                                echo 'No major assigned.';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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
