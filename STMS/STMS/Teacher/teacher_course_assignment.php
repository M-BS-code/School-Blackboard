<?php
session_start();
include('../includes/config.php');

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    // Redirect to teacher login page if not logged in
    header("Location: teacherLoginPage.php");
    exit;
}

// Check if course_id is set in the URL
if (!isset($_GET['course_id'])) {
    // Redirect to course selection page if course_id is not set
    header("Location: teacher_courses.php");
    exit;
}

$_SESSION['course_id'] = $_GET['course_id'];

// Fetch course details
$course_id = $_SESSION['course_id'];
$sql_course = "SELECT * FROM Courses WHERE course_id = $course_id AND instructor_id = {$_SESSION['teacher_id']}";
$result_course = $conn->query($sql_course);
if ($result_course->num_rows == 0) {
    // Redirect to course selection page if course does not exist or teacher is not assigned to the course
    header("Location: teacher_courses.php");
    exit;
}
$course = $result_course->fetch_assoc();

// Fetch existing assignments for the course
$sql_assignments = "SELECT * FROM Assignments WHERE course_id = $course_id";
$result_assignments = $conn->query($sql_assignments);
$assignments = [];
if ($result_assignments->num_rows > 0) {
    while ($row_assignment = $result_assignments->fetch_assoc()) {
        $assignments[] = $row_assignment;
    }
}

// Handle form submission for adding new assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_assignment'])) {
    // Process form submission
    $name = $_POST['name'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $link = $_POST['link'];

    // Insert assignment into database
    $sql_insert_assignment = "INSERT INTO Assignments (assignment_name, course_id, due_date, description, link) VALUES ('$name', '$course_id', '$due_date', '$description', '$link')";
    if ($conn->query($sql_insert_assignment) === TRUE) {
        // Redirect to refresh the page
        header("Location: teacher_course_assignment.php?course_id=$course_id");
        exit;
    } else {
        echo "Error: " . $sql_insert_assignment . "<br>" . $conn->error;
    }
}

// Handle form submission for deleting an assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_assignment'])) {
    $assignment_id = $_POST['assignment_id'];
    $sql_delete_assignment = "DELETE FROM Assignments WHERE assignment_id = $assignment_id";
    if ($conn->query($sql_delete_assignment) === TRUE) {
        // Redirect to refresh the page
        header("Location: teacher_course_assignment.php?course_id=$course_id");
        exit;
    } else {
        echo "Error: " . $sql_delete_assignment . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course - <?php echo $course['course_name']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">

    <style>


:root {
    --white-color: #fff;
    --paraText-color: #777;
    --heading-color: #333;
    --primary-color: rgb(31, 153, 167);
    --secondary-color: rgb(94, 7, 40);
}
 


body {
            background-color: #f8f9fa; /* Light gray background */
        }

        .content {
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .card-title {
            margin-bottom: 10px; /* Added margin bottom */
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff; /* Primary blue button */
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3;
        }

        .assignment-card {
            background-color: #ffffff; /* White card background */
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Light shadow */
        }

        .hamburger {
            cursor: pointer;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            background-color: white;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .hamburger .line {
            width: 30px;
            height: 3px;
            background-color: white;
            transition: all 0.3s ease;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 2;
            top: 0;
            left: 0;
            background-color: var(--secondary-color);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            border-top-right-radius: 25px;
            border-bottom-right-radius: 25px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar-title {
            color: var(--white-color);
            font-size: 32px;
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid var(--white-color);
            margin-bottom: 20px;
        }

        .sidebar ul li a,
        .dropbtn {
            padding: 8px 15px;
            text-decoration: none;
            font-size: 18px;
            color: var(--white-color);
            display: block;
            transition: color 0.3s, background-color 0.3s;
        }

        .sidebar ul li a:hover,
        .dropbtn:hover {
            color: var(--primary-color);
            background-color: var(--paraText-color);
        }

        .dropdown {
            position: relative;
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--secondary-color);
            min-width: 100%;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: var(--secondary-color);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            font-size: 10px;
            margin-left: 25px;
        }

        .dropdown-content a:hover {
            background-color: var(--paraText-color);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .sidebar ul li:hover .dropdown-content {
            display: block;
        }

        .closebtn {
            position: absolute;
            top: 0;
            right: 10px;
            font-size: 36px;
            margin-left: 50px;
            color: white;
        }

        .closebtn:hover {
            color: #ccc;
        }

        .dropdown-content .logout-separator {
            border-top: 1px solid var(--paraText-color);
            margin: 10px 0;
        }

        .dropdown-content .logout {
            padding-top: 12px;
        }

        .dropdown:hover .dropdown-content,
        .sidebar ul li:hover .dropdown-content {
            display: block;
            position: static;
        }



        .content {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* Align children to the start horizontally */
    padding: 20px;
    margin-top: 60px; /* Adjust based on your header's height */
    min-height: calc(100vh - 120px); /* Adjust based on your header/footer height */
    width: 100%;
}

.card {
    width: 100%; /* Adjust this to set a preferred width for your cards */
    max-width: 1100px; /* Example max-width, adjust as needed */
    /* Remaining card styles */
}



        .header {
    display: flex;
    align-items: center;
    justify-content: center; /* Center align items horizontally */
    padding: 10px 20px;
    background-color: var(--secondary-color);
    color: var(--white-color);
}

.page-title {
    font-size: 24px;
    font-weight: bold;
    margin: 0; /* Remove default margin */
}

.menu-toggle {
    cursor: pointer;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    background-color: var(--secondary-color); /* Change background color to secondary color */
    border-top-left-radius: 15px; /* Adjusted border radius */
    border-bottom-left-radius: 15px; /* Adjusted border radius */
}

.menu-toggle .line {
    width: 30px;
    height: 3px;
    background-color: white; /* Change line color to white */
    transition: all 0.3s ease;
}
 
    .header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Adjust this to space between */
    padding: 10px 20px;
    background-color: var(--secondary-color);
    color: var(--white-color);
}


.menu-toggle {
    margin-right: 20px; /* Add space between the toggle and the title */
}

.page-title {
    margin: 20px; /* Ensures there's some space if the toggle is too close */
}


.menu-toggle-placeholder {
    width: 40px; /* Match the width of your menu-toggle */
    height: 24px; /* Match the height of your menu-toggle, adjust as needed */
    visibility: hidden; /* Makes the placeholder invisible */
}

    </style>
</head>
<body>


<div class="header">
    <div class="menu-toggle" onclick="toggleMenu()">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <h2 class="page-title">Upload Assignments</h2>
    <div class="menu-toggle-placeholder"></div> <!-- Placeholder for balancing -->
</div>

    <div class="sidebar" id="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <div class="sidebar-title">Teacher</div>
        <ul>
            <li><a href="teacher_dashboard.php">Dashboard</a></li>
            <li><a href="teacher_courses.php">Course</a></li>
            <li class="menu-item dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown(this)">Upload Grades</a>
                <div class="dropdown-content">
                    <a href="teacher_upload_assignments_grades.php">Assignments</a>
                    <a href="teacher_upload_quizes_marks.php">Quizzes</a>
                    <a href="teacher_upload_final_exam_marks.php">Finals</a>
                </div>
            </li>
            <li><a href="logout.php">Logout</a></li> 
        </ul>
    </div>   
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Course: <?php echo $course['course_name']; ?></h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Add New Assignment</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date:</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="link">Google Drive Link:</label>
                        <input type="url" name="link" id="link" class="form-control" required>
                    </div>
                    <button type="submit" name="add_assignment" class="btn btn-primary">Add Assignment</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Assignments</h3>
                <div class="row">
                    <?php foreach ($assignments as $assignment) { ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $assignment['assignment_name']; ?></h5>
                                    <p class="card-text"><?php echo $assignment['description']; ?></p>
                                    <p class="card-text"><strong>Due Date:</strong> <?php echo $assignment['due_date']; ?></p>
                                    <a href="<?php echo $assignment['link']; ?>" class="btn btn-primary">Download</a>
                                    <!-- Form for deleting the assignment -->
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="assignment_id" value="<?php echo $assignment['assignment_id']; ?>">
                                        <button type="submit" name="delete_assignment" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Add JavaScript functionalities here
    </script>




<script>
  function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    sidebar.style.width = sidebar.style.width === "250px" ? "0" : "250px";
}

function closeNav() {
    document.getElementById("sidebar").style.width = "0";
}

function toggleDropdown(element) {
    var dropdownContent = element.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
}

// Close the dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === "block") {
                openDropdown.style.display = "none";
            }
        }
    }
};



</script>
</body>
</html>
