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
    $sql = "SELECT user_id, username FROM users WHERE role='teacher'";
    $result = $conn->query($sql);
    $teachers = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
    }
    return $teachers;
}

// Function to fetch all majors from the database
function getAllMajors() {
    global $conn;
    $sql = "SELECT major_id, major_name FROM major";
    $result = $conn->query($sql);
    $majors = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $majors[] = $row;
        }
    }
    return $majors;
}

// Function to fetch all courses from the database
function getAllCourses() {
    global $conn;
    $sql = "SELECT c.*, u.username AS instructor_name
            FROM courses c
            INNER JOIN users u ON c.instructor_id = u.user_id";
    $result = $conn->query($sql);
    $courses = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    return $courses;
}

// Fetch all teachers
$teachers = getAllTeachers();

// Fetch all majors
$majors = getAllMajors();

// Fetch all courses
$courses = getAllCourses();

// Update mode variables
$update_mode = false;
$update_course_id = '';
$update_course_name = '';
$update_course_instructor_id = '';
$update_course_description = '';
$update_course_semester = '';
$update_major_id = '';

// Process Add or Update Course
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_course'])) {
        // Add Course
        $course_name = $_POST['course_name'];
        $course_instructor_id = $_POST['course_instructor_id'];
        $course_description = $_POST['course_description'];
        $course_semester = $_POST['semester'];
        $major_id = $_POST['major_id'];

        // Insert course data into Courses table
        $sql = "INSERT INTO courses (course_name, instructor_id, course_description, semester, major_id) 
                VALUES ('$course_name', '$course_instructor_id', '$course_description', '$course_semester', '$major_id')";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_courses.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update_course'])) {
        // Update mode
        $update_mode = true;
        $update_course_id = $_POST['course_id'];
        $update_course_name = $_POST['course_name'];
        $update_course_instructor_id = $_POST['course_instructor_id'];
        $update_course_description = $_POST['course_description'];
        $update_course_semester = $_POST['semester'];
        $update_major_id = $_POST['major_id'];
    } elseif (isset($_POST['save_update'])) {
        // Save Update
        $course_id = $_POST['course_id'];
        $course_name = $_POST['course_name'];
        $course_instructor_id = $_POST['course_instructor_id'];
        $course_description = $_POST['course_description'];
        $course_semester = $_POST['semester'];
        $major_id = $_POST['major_id'];

        // Update course data in Courses table
        $sql = "UPDATE courses SET course_name='$course_name', instructor_id='$course_instructor_id', 
                course_description='$course_description', semester='$course_semester', major_id='$major_id' 
                WHERE course_id=$course_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_courses.php"); // Redirect to refresh the page
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Delete Course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];

    // Delete course data from Courses table
    $sql = "DELETE FROM courses WHERE course_id=$course_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_courses.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Courses</title>
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
    
    /* Styles to make all table text white */
    .table thead th, /* Header cells */
    .table tbody tr td { /* Data cells */
        color: black !important; 
    }h1 {
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
            margin-right: 500px; 
            justify-content: center;
        }
        /*
        .content{
            margin-left: 250px;
        } */  
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">  

            <div class="col-md-12 dashboard-header">
            <?php include('AdminNavbar.php'); ?>
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i>Admin Courses</h1>
            </div>
        </div>
    </div>
        <div class="content">
            <h1>Admin Courses</h1>
            <!-- Add or Update Course Form -->
            <div class="card">
                <div class="card-header">
                    <h2><?php echo $update_mode ? 'Update Course' : 'Add Course'; ?></h2>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <?php if($update_mode) { ?>
                            <input type="hidden" name="course_id" value="<?php echo $update_course_id; ?>">
                        <?php } ?>
                        <div class="form-group">
                            <label for="course_name">Course Name:</label>
                            <input type="text" class="form-control" name="course_name" value="<?php echo $update_mode ? $update_course_name : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="course_instructor_id">Instructor:</label>
                            <select class="form-control" name="course_instructor_id">
                                <?php
                                foreach ($teachers as $teacher) {
                                    $teacher_id = $teacher['user_id'];
                                    $teacher_name = $teacher['username'];
                                    $selected = ($update_mode && $teacher_id == $update_course_instructor_id) ? 'selected' : '';
                                    echo "<option value='$teacher_id' $selected>$teacher_name</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="major_id">Major:</label>
                            <select class="form-control" name="major_id">
                                <?php
                                foreach ($majors as $major) {
                                    $major_id = $major['major_id'];
                                    $major_name = $major['major_name'];
                                    $selected = ($update_mode && $major_id == $update_major_id) ? 'selected' : '';
                                    echo "<option value='$major_id' $selected>$major_name</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="course_description">Description:</label>
                            <textarea class="form-control" name="course_description"><?php echo $update_mode ? $update_course_description : ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="semester">Semester/Term:</label>
                            <input type="text" class="form-control" name="semester" value="<?php echo $update_mode ? $update_course_semester : ''; ?>" required>
                        </div>
                        <?php if(!$update_mode) { ?>
                            <button type="submit" class="btn btn-primary" name="add_course">Add Course</button>
                        <?php } else { ?>
                            <button type="submit" class="btn btn-primary" name="save_update">Save Update</button>
                        <?php } ?>
                    </form>
                </div>
            </div>

            <!-- List of Courses -->
            <div class="card mt-4">
                <div class="card-header">
                    <h2>Courses</h2>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Instructor</th>
                                <th>Description</th>
                                <th>Semester</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course) { ?>
                            <tr>
                                <td><?php echo $course['course_name']; ?></td>
                                <td><?php echo $course['instructor_name']; ?></td>
                                <td><?php echo $course['course_description']; ?></td>
                                <td><?php echo $course['semester']; ?></td>
                                <td>
                                    <!-- Update Course Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                        <input type="hidden" name="course_name" value="<?php echo $course['course_name']; ?>">
                                        <input type="hidden" name="course_instructor_id" value="<?php echo $course['instructor_id']; ?>">
                                        <input type="hidden" name="course_description" value="<?php echo $course['course_description']; ?>">
                                        <input type="hidden" name="semester" value="<?php echo $course['semester']; ?>">
                                        <input type="hidden" name="major_id" value="<?php echo $course['major_id']; ?>">
                                        <button type="submit" class="btn btn-info btn-sm" name="update_course">Update</button>
                                    </form>
                                    <!-- Delete Course Form -->
                                    <form class="d-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="delete_course">Delete</button>
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

